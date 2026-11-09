<?php

/**
 * Talks to the NameCrane API (/api/v1, https://namecrane.com/docs/api) with a reseller's API key.
 *
 * Every call goes to the module log through $logger, with any password masked.
 */
class NamecraneMailApi
{
    public const DEFAULT_HOST = 'namecrane.com';

    private string $base_url;

    private string $api_key;

    private string $user_agent;

    /** @var callable|null function (string $label, string $data, string $direction, bool $success) */
    private $logger;

    /**
     * @param string $api_key The reseller's NameCrane API key
     * @param string|null $host The API host, namecrane.com when empty
     * @param string $user_agent Names the module and its version to NameCrane
     * @param callable|null $logger Receives each request and response for the module log
     */
    public function __construct(string $api_key, ?string $host = null, string $user_agent = 'NameCrane-Blesta-Mail', ?callable $logger = null)
    {
        $host = rtrim(preg_replace('#^https?://#', '', strtolower(trim((string) $host))), '/');

        $this->base_url = 'https://' . ($host === '' ? self::DEFAULT_HOST : $host) . '/api/v1/';
        $this->api_key = trim($api_key);
        $this->user_agent = $user_agent;
        $this->logger = $logger;
    }

    /**
     * @throws NamecraneMailApiException
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path . ($query ? '?' . http_build_query($query) : ''));
    }

    /**
     * @throws NamecraneMailApiException
     */
    public function post(string $path, array $data = []): array
    {
        return $this->request('POST', $path, $data);
    }

    /**
     * @throws NamecraneMailApiException
     */
    public function put(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, $data);
    }

    /**
     * @throws NamecraneMailApiException
     */
    public function delete(string $path): array
    {
        return $this->request('DELETE', $path);
    }

    /**
     * A path segment (domain, mailbox or alias name) made safe for the URL.
     */
    public static function segment(string $segment): string
    {
        return rawurlencode(strtolower(trim($segment)));
    }

    /**
     * @throws NamecraneMailApiException
     */
    private function request(string $method, string $path, ?array $data = null): array
    {
        if ($this->api_key === '') {
            throw new NamecraneMailApiException('No API key: add your NameCrane API key to this module\'s server.');
        }

        $url = $this->base_url . ltrim($path, '/');
        $this->log($method . ' ' . $url, $data === null ? '' : json_encode(self::masked($data)), 'input', true);

        $curl = curl_init($url);
        $headers = [
            'Authorization: Bearer ' . $this->api_key,
            'Accept: application/json',
            'User-Agent: ' . $this->user_agent,
        ];

        if ($data !== null) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
        ]);

        $raw = curl_exec($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        $body = is_string($raw) ? json_decode($raw, true) : null;
        $this->log($method . ' ' . $url, is_string($raw) ? self::maskedResponse($raw) : $error, 'output', is_array($body) && $status < 400);

        if ($raw === false) {
            throw new NamecraneMailApiException('Could not reach NameCrane: ' . $error);
        }

        if (!is_array($body)) {
            throw new NamecraneMailApiException('NameCrane sent back something unexpected (HTTP ' . $status . '). Try again shortly.', $status);
        }

        if ($status >= 400) {
            throw new NamecraneMailApiException(self::errorMessage($body, $status), $status, $body);
        }

        return $body;
    }

    private function log(string $label, string $data, string $direction, bool $success): void
    {
        if ($this->logger) {
            call_user_func($this->logger, $label, $data, $direction, $success);
        }
    }

    /**
     * The first validation message, else the error, else the HTTP status.
     */
    private static function errorMessage(array $body, int $status): string
    {
        if (!empty($body['errors']) && is_array($body['errors'])) {
            foreach ($body['errors'] as $messages) {
                return is_array($messages) ? (string) reset($messages) : (string) $messages;
            }
        }

        if (!empty($body['error'])) {
            return (string) $body['error'];
        }

        return !empty($body['message']) ? (string) $body['message'] : 'NameCrane refused the request (HTTP ' . $status . ').';
    }

    /**
     * The request as the module log shows it, the password hidden.
     */
    private static function masked(array $data): array
    {
        if (isset($data['password'])) {
            $data['password'] = '********';
        }

        return $data;
    }

    /**
     * The response as the module log shows it, a new domain's admin password hidden.
     */
    private static function maskedResponse(string $raw): string
    {
        $body = json_decode($raw, true);

        if (!is_array($body) || !isset($body['admin']['password'])) {
            return $raw;
        }

        $body['admin']['password'] = '********';

        return json_encode($body);
    }
}

/**
 * NameCrane's refusal of a call: its message, the HTTP status (0 when NameCrane couldn't be
 * reached) and the decoded body.
 */
class NamecraneMailApiException extends Exception
{
    private int $status;

    private array $body;

    public function __construct(string $message, int $status = 0, array $body = [])
    {
        parent::__construct($message);
        $this->status = $status;
        $this->body = $body;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function body(): array
    {
        return $this->body;
    }
}
