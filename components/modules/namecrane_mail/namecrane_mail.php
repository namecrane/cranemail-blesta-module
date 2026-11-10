<?php

/**
 * NameCrane Mail for Blesta: sells CraneMail email domains from your NameCrane reseller plan.
 *
 * A server holds your NameCrane API key (and, only when the key covers more than one reseller
 * plan, the plan's service ID). A package sets the domain's limits and features; configurable
 * options named after them override them per order. Clients get the domain's overview and,
 * unless the package hides them, pages to manage its mailboxes, aliases and domain aliases.
 *
 * Field, option and package names are the 1.x module's, so updating keeps existing servers,
 * packages and services as they are.
 */
class NamecraneMail extends Module
{
    /** Alias options as the API names them. */
    private const ALIAS_FEATURES = ['catch_all', 'show_in_gal', 'internal_only', 'send_from', 'all_users'];

    /** How many years mail can be archived for. */
    private const ARCHIVE_YEARS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20];

    /** The package's switches; each is on unless set to off. */
    private const SWITCHES = ['webmail_sso', 'mxfilter_sso', 'client_management'];

    public function __construct()
    {
        $this->loadConfig(__DIR__ . DS . 'config.json');
        Loader::loadComponents($this, ['Input']);
        Language::loadLang('namecrane_mail', null, __DIR__ . DS . 'language' . DS);
        Configure::load('namecrane_mail', __DIR__ . DS . 'config' . DS);
        Loader::load(__DIR__ . DS . 'apis' . DS . 'namecrane_mail_api.php');
    }

    // Servers

    public function manageModule($module, array &$vars)
    {
        $view = $this->view('manage');
        $view->set('module', $module);

        return $view->fetch();
    }

    public function manageAddRow(array &$vars)
    {
        $view = $this->view('add_row');
        $view->set('vars', (object) $vars);

        return $view->fetch();
    }

    public function manageEditRow($module_row, array &$vars)
    {
        if (empty($vars)) {
            $vars = (array) $module_row->meta;
        }

        $view = $this->view('edit_row');
        $view->set('vars', (object) $vars);

        return $view->fetch();
    }

    public function addModuleRow(array &$vars)
    {
        return $this->rowMeta($vars);
    }

    public function editModuleRow($module_row, array &$vars)
    {
        return $this->rowMeta($vars);
    }

    /**
     * A server's settings once its API key works: the key reaches a reseller plan, the one
     * named by Service ID when it reaches several.
     *
     * @return array|null The row's meta, or null with errors set
     */
    private function rowMeta(array &$vars)
    {
        $vars = array_merge(['server_name' => '', 'api_key' => '', 'service_id' => ''], $vars);
        $vars['api_key'] = trim((string) $vars['api_key']);
        $vars['service_id'] = trim((string) $vars['service_id']);

        $this->Input->setRules([
            'server_name' => [
                'empty' => ['rule' => 'isEmpty', 'negate' => true, 'message' => Language::_('NamecraneMail.!error.server_name.valid', true)],
            ],
            'api_key' => [
                'empty' => ['rule' => 'isEmpty', 'negate' => true, 'message' => Language::_('NamecraneMail.!error.api_key.valid', true)],
            ],
            'service_id' => [
                'format' => ['rule' => [[$this, 'validServiceId']], 'message' => Language::_('NamecraneMail.!error.service_id.valid', true)],
            ],
        ]);

        if (!$this->Input->validates($vars)) {
            return null;
        }

        $problem = $this->connectionProblem($vars['api_key'], $vars['service_id'] === '' ? null : (int) $vars['service_id']);

        if ($problem !== null) {
            $this->Input->setErrors(['api_key' => ['connection' => $problem]]);

            return null;
        }

        return [
            ['key' => 'server_name', 'value' => trim((string) $vars['server_name']), 'encrypted' => 0],
            ['key' => 'api_key', 'value' => $vars['api_key'], 'encrypted' => 1],
            ['key' => 'service_id', 'value' => $vars['service_id'], 'encrypted' => 0],
        ];
    }

    /**
     * Why a key can't be used for this server, or null when it can.
     */
    private function connectionProblem(string $api_key, ?int $service_id): ?string
    {
        try {
            $services = $this->client($api_key)->get('cranemail/services');
        } catch (NamecraneMailApiException $e) {
            return $e->status() === 401 ? Language::_('NamecraneMail.!error.api_key.refused', true, $e->getMessage()) : $e->getMessage();
        }

        if ($services === []) {
            return Language::_('NamecraneMail.!error.api_key.no_plan', true);
        }

        if ($service_id !== null) {
            foreach ($services as $service) {
                if ((int) $service['id'] === $service_id) {
                    return null;
                }
            }

            return Language::_('NamecraneMail.!error.service_id.unreachable', true, $service_id, $this->describeServices($services));
        }

        if (count($services) > 1) {
            return Language::_('NamecraneMail.!error.service_id.needed', true, $this->describeServices($services));
        }

        return null;
    }

    public function validServiceId($value): bool
    {
        return $value === '' || $value === null || (ctype_digit((string) $value) && (int) $value > 0);
    }

    // Packages

    public function getPackageFields($vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();
        $meta = isset($vars->meta) ? (array) $vars->meta : [];

        foreach ($this->packageOptions() as $key => $option) {
            $id = 'namecrane_mail_' . $key;
            $value = $meta[$key] ?? $option['default'];
            $label = $fields->label(Language::_('NamecraneMail.package_fields.' . $key, true), $id);

            if (isset($option['options'])) {
                $label->attach($fields->fieldSelect('meta[' . $key . ']', $option['options'], $value, ['id' => $id, 'class' => 'form-select']));
            } else {
                $label->attach($fields->fieldText('meta[' . $key . ']', $value, ['id' => $id, 'class' => 'form-control']));
            }

            if (!empty($option['tooltip'])) {
                $label->attach($fields->tooltip(Language::_('NamecraneMail.package_fields.tooltip.' . $key, true)));
            }

            $fields->setField($label);
        }

        return $fields;
    }

    public function addPackage(?array $vars = null)
    {
        return $this->packageMeta((array) $vars);
    }

    public function editPackage($package, ?array $vars = null)
    {
        return $this->packageMeta((array) $vars);
    }

    /**
     * @return array|null The package's meta, or null with errors set
     */
    private function packageMeta(array $vars)
    {
        $this->Input->setRules($this->packageRules());

        if (!$this->Input->validates($vars)) {
            return null;
        }

        $meta = [];

        foreach ((array) ($vars['meta'] ?? []) as $key => $value) {
            $meta[] = ['key' => $key, 'value' => $value, 'encrypted' => 0];
        }

        return $meta;
    }

    /**
     * The package form's options in order, with their defaults, choices and whether they have
     * a tooltip.
     */
    private function packageOptions(): array
    {
        $on_off = ['0' => Language::_('NamecraneMail.option.disabled', true), '1' => Language::_('NamecraneMail.option.enabled', true)];
        $allowed = ['on' => Language::_('NamecraneMail.option.allowed', true), 'off' => Language::_('NamecraneMail.option.turned_off', true)];
        $years = [];

        foreach (self::ARCHIVE_YEARS as $year) {
            $years[(string) $year] = $year === 0 ? Language::_('NamecraneMail.option.disabled', true) : Language::_($year === 1 ? 'NamecraneMail.option.year' : 'NamecraneMail.option.years', true, $year);
        }

        return [
            'disklimit' => ['default' => '1'],
            'userlimit' => ['default' => '0', 'tooltip' => true],
            'useraliaslimit' => ['default' => '0', 'tooltip' => true],
            'domainaliaslimit' => ['default' => '0', 'tooltip' => true],
            'archive_years' => ['default' => '0', 'options' => $years],
            'archive_direction' => ['default' => 'inout', 'options' => [
                'in' => Language::_('NamecraneMail.archive_direction.in', true),
                'out' => Language::_('NamecraneMail.archive_direction.out', true),
                'inout' => Language::_('NamecraneMail.archive_direction.inout', true),
            ]],
            'spamexperts' => ['default' => '1', 'options' => $on_off],
            'spamexperts_adminaccess' => ['default' => 'primary', 'tooltip' => true, 'options' => [
                'primary' => Language::_('NamecraneMail.option.primary_admin', true),
                'all' => Language::_('NamecraneMail.option.all_admins', true),
            ]],
            'filestorage' => ['default' => '0', 'options' => $on_off],
            'office' => ['default' => '0', 'options' => $on_off],
            'webmail_sso' => ['default' => 'on', 'tooltip' => true, 'options' => $allowed],
            'mxfilter_sso' => ['default' => 'on', 'tooltip' => true, 'options' => $allowed],
            'client_management' => ['default' => 'on', 'tooltip' => true, 'options' => [
                'on' => Language::_('NamecraneMail.option.clients_manage', true),
                'off' => Language::_('NamecraneMail.option.hidden_from_clients', true),
            ]],
        ];
    }

    private function packageRules(): array
    {
        $rule = function ($check, string $key) {
            return ['valid' => ['rule' => $check, 'message' => Language::_('NamecraneMail.!error.' . $key . '.valid', true)]];
        };
        $count = [[$this, 'validInteger']];

        $rules = [
            'meta[disklimit]' => $rule($count, 'disklimit'),
            'meta[userlimit]' => $rule($count, 'userlimit'),
            'meta[useraliaslimit]' => $rule($count, 'useraliaslimit'),
            'meta[domainaliaslimit]' => $rule($count, 'domainaliaslimit'),
            'meta[archive_years]' => $rule([[$this, 'validArchiveYears']], 'archive_years'),
            'meta[archive_direction]' => $rule(['in_array', ['in', 'out', 'inout']], 'archive_direction'),
            'meta[spamexperts]' => $rule(['in_array', ['0', '1']], 'spamexperts'),
            'meta[spamexperts_adminaccess]' => $rule(['in_array', ['primary', 'all']], 'spamexperts_adminaccess'),
            'meta[filestorage]' => $rule(['in_array', ['0', '1']], 'filestorage'),
            'meta[office]' => $rule(['in_array', ['0', '1']], 'office'),
        ];

        foreach (self::SWITCHES as $switch) {
            $rules['meta[' . $switch . ']'] = $rule(['in_array', ['on', 'off']], 'switch');
        }

        return $rules;
    }

    public function validInteger($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) !== false;
    }

    public function validArchiveYears($value): bool
    {
        return in_array((string) $value, array_map('strval', self::ARCHIVE_YEARS), true);
    }

    // Services

    public function getServiceName($service)
    {
        return strtolower((string) ($this->serviceFieldsToObject($service->fields)->namecrane_mail_domain ?? '')) ?: null;
    }

    public function getPackageServiceName($package, $vars = null)
    {
        $vars = (array) $vars;

        return isset($vars['namecrane_mail_domain']) ? strtolower(trim((string) $vars['namecrane_mail_domain'])) : null;
    }

    public function getAdminAddFields($package, $vars = null)
    {
        return $this->domainField($vars);
    }

    public function getClientAddFields($package, $vars = null)
    {
        return $this->domainField($vars);
    }

    public function getAdminEditFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = $this->domainField($vars);

        foreach (['username' => 'fieldText', 'password' => 'fieldText'] as $name => $type) {
            $key = 'namecrane_mail_' . $name;
            $label = $fields->label(Language::_('NamecraneMail.service_fields.' . $name, true), $key);
            $label->attach($fields->{$type}($key, $vars->{$key} ?? null, ['id' => $key, 'class' => 'form-control']));
            $fields->setField($label);
        }

        return $fields;
    }

    private function domainField($vars)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();
        $label = $fields->label(Language::_('NamecraneMail.service_fields.domain', true), 'namecrane_mail_domain');
        $label->attach($fields->fieldText('namecrane_mail_domain', $vars->namecrane_mail_domain ?? null, ['id' => 'namecrane_mail_domain', 'class' => 'form-control']));
        $fields->setField($label);

        return $fields;
    }

    public function validateService($package, ?array $vars = null)
    {
        $this->Input->setRules([
            'namecrane_mail_domain' => [
                'format' => ['rule' => [[$this, 'validDomain']], 'message' => Language::_('NamecraneMail.!error.domain.valid', true)],
            ],
        ]);

        return $this->Input->validates($vars);
    }

    public function validateServiceEdit($service, ?array $vars = null)
    {
        if (!isset($vars['namecrane_mail_domain'])) {
            return true;
        }

        return $this->validateService(null, $vars);
    }

    public function validDomain($value): bool
    {
        return (bool) preg_match('/^(?=.{4,253}$)([a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z][a-z0-9-]{0,61}[a-z0-9]$/i', trim((string) $value));
    }

    public function addService($package, ?array $vars = null, $parent_package = null, $parent_service = null, $status = 'pending')
    {
        $vars = (array) $vars;
        $vars['namecrane_mail_domain'] = strtolower(trim((string) ($vars['namecrane_mail_domain'] ?? '')));

        if (!$this->validateService($package, $vars)) {
            return;
        }

        $username = trim((string) ($vars['namecrane_mail_username'] ?? ''));
        $password = (string) ($vars['namecrane_mail_password'] ?? '');

        if ($this->usesModule($vars)) {
            if (!($row = $this->row())) {
                return;
            }

            $request = ['domain' => $vars['namecrane_mail_domain']] + $this->domainSettings($package, $this->configOptions($vars));

            if (!empty($row->meta->service_id)) {
                $request['service_id'] = (int) $row->meta->service_id;
            }

            try {
                $created = $this->api($row)->post('cranemail/domains', $request);
            } catch (NamecraneMailApiException $e) {
                $this->apiError($e);

                return;
            }

            $username = (string) ($created['admin']['username'] ?? $username);
            $password = (string) ($created['admin']['password'] ?? $password);
        }

        return $this->serviceFields($vars['namecrane_mail_domain'], $username, $password);
    }

    /**
     * Saves the service's fields and, through the module, applies its package and options to
     * the domain again. The domain itself can't be renamed at NameCrane: changing it without the
     * module only changes Blesta's record.
     */
    public function editService($package, $service, ?array $vars = null, $parent_package = null, $parent_service = null)
    {
        $vars = (array) $vars;
        $current = $this->serviceFieldsToObject($service->fields);
        $domain = strtolower((string) ($current->namecrane_mail_domain ?? ''));
        $new_domain = isset($vars['namecrane_mail_domain']) ? strtolower(trim((string) $vars['namecrane_mail_domain'])) : $domain;

        if (!$this->validateServiceEdit($service, ['namecrane_mail_domain' => $new_domain])) {
            return;
        }

        if ($this->usesModule($vars)) {
            if ($new_domain !== $domain) {
                $this->Input->setErrors(['namecrane_mail_domain' => ['renamed' => Language::_('NamecraneMail.!error.domain.renamed', true)]]);

                return;
            }

            if (!$this->updateSettings($package, $service, $this->configOptions($vars, $service))) {
                return;
            }
        }

        return $this->serviceFields(
            $new_domain,
            trim((string) ($vars['namecrane_mail_username'] ?? $current->namecrane_mail_username ?? '')),
            (string) ($vars['namecrane_mail_password'] ?? $current->namecrane_mail_password ?? '')
        );
    }

    public function suspendService($package, $service, $parent_package = null, $parent_service = null)
    {
        $this->domainCall($service, 'post', '/suspend');

        return null;
    }

    public function unsuspendService($package, $service, $parent_package = null, $parent_service = null)
    {
        // Unsuspending a domain that isn't suspended leaves it as it should be.
        $this->domainCall($service, 'post', '/unsuspend', function (NamecraneMailApiException $e) {
            return $e->status() === 400 && $e->getMessage() === 'This domain is not suspended.';
        });

        return null;
    }

    public function cancelService($package, $service, $parent_package = null, $parent_service = null)
    {
        // Already gone is what canceling wants.
        $this->domainCall($service, 'delete', '', function (NamecraneMailApiException $e) {
            return $e->status() === 404;
        });

        return null;
    }

    public function changeServicePackage($package_from, $package_to, $service, $parent_package = null, $parent_service = null)
    {
        $this->updateSettings($package_to, $service, $this->configOptions(null, $service));

        return null;
    }

    /**
     * Sends the package's settings (with the service's options) to the domain.
     */
    private function updateSettings($package, $service, array $options): bool
    {
        return $this->domainCall($service, 'put', '/settings', null, $this->domainSettings($package, $options));
    }

    /**
     * Calls the API on the service's domain, setting the error unless $ignore accepts it.
     */
    private function domainCall($service, string $method, string $suffix, ?callable $ignore = null, ?array $data = null): bool
    {
        if (!($row = $this->row($service))) {
            return false;
        }

        try {
            $path = 'cranemail/domains/' . NamecraneMailApi::segment($this->domainOf($service)) . $suffix;
            $data === null ? $this->api($row)->{$method}($path) : $this->api($row)->{$method}($path, $data);
        } catch (NamecraneMailApiException $e) {
            if ($ignore && $ignore($e)) {
                return true;
            }

            $this->apiError($e);

            return false;
        }

        return true;
    }

    private function serviceFields(string $domain, string $username, string $password): array
    {
        return [
            ['key' => 'namecrane_mail_domain', 'value' => $domain, 'encrypted' => 0],
            ['key' => 'namecrane_mail_username', 'value' => $username, 'encrypted' => 0],
            ['key' => 'namecrane_mail_password', 'value' => $password, 'encrypted' => 1],
        ];
    }

    /**
     * The domain settings for the API's create and settings calls: the package's, with
     * configurable options named after them taking over.
     */
    private function domainSettings($package, array $options): array
    {
        $meta = isset($package->meta) ? (array) $package->meta : [];
        $value = function (string $key, $default) use ($meta, $options) {
            if (isset($options[$key]) && $options[$key] !== '') {
                return $options[$key];
            }

            return isset($meta[$key]) && $meta[$key] !== '' ? $meta[$key] : $default;
        };

        $years = (int) $value('archive_years', 0);
        $direction = (string) $value('archive_direction', 'inout');
        $access = (string) $value('spamexperts_adminaccess', 'primary');

        return [
            'disk_limit' => max(0, (int) $value('disklimit', 1)),
            'user_limit' => max(0, (int) $value('userlimit', 0)),
            'user_alias_limit' => max(0, (int) $value('useraliaslimit', 0)),
            'domain_alias_limit' => max(0, (int) $value('domainaliaslimit', 0)),
            'mxfilter_enabled' => (bool) $value('spamexperts', 0),
            'mxfilter_admin_access' => in_array($access, ['primary', 'all'], true) ? $access : 'primary',
            'archive_years' => min(20, max(0, $years)),
            'archive_direction' => $years > 0 ? (in_array($direction, ['in', 'out', 'inout'], true) ? $direction : 'inout') : 'disabled',
            'file_storage' => (bool) $value('filestorage', 0),
            'office' => (bool) $value('office', 0),
            'webmail_sso' => $this->allows($package, 'webmail_sso'),
            'mxfilter_sso' => $this->allows($package, 'mxfilter_sso'),
        ];
    }

    /**
     * The configurable options chosen for the service: those being submitted, else those it has.
     * A quantity option gives its quantity.
     */
    private function configOptions(?array $vars, $service = null): array
    {
        if (isset($vars['configoptions']) && is_array($vars['configoptions'])) {
            return $vars['configoptions'];
        }

        $options = [];

        foreach ((array) ($service->options ?? []) as $option) {
            $options[$option->option_name] = ($option->option_type ?? null) === 'quantity' ? ($option->qty ?? $option->option_value) : $option->option_value;
        }

        return $options;
    }

    /**
     * Whether the package leaves one of its switches (webmail_sso, mxfilter_sso,
     * client_management) on. Packages from 1.x don't have them, and get them on.
     */
    private function allows($package, string $switch): bool
    {
        return (string) ($package->meta->{$switch} ?? 'on') !== 'off';
    }

    private function usesModule(array $vars): bool
    {
        return isset($vars['use_module']) && ($vars['use_module'] === true || $vars['use_module'] === 'true');
    }

    // Service pages

    public function getAdminServiceInfo($service, $package)
    {
        return $this->serviceInfo($service);
    }

    public function getClientServiceInfo($service, $package)
    {
        return $this->serviceInfo($service);
    }

    private function serviceInfo($service): string
    {
        $view = $this->view('service_info');
        $view->set('service_fields', $this->serviceFieldsToObject($service->fields));

        return $view->fetch();
    }

    public function getAdminTabs($package)
    {
        return ['tabAdminOverview' => Language::_('NamecraneMail.tabs.overview', true)];
    }

    public function getClientTabs($package)
    {
        $tabs = ['tabClientOverview' => ['name' => Language::_('NamecraneMail.tabs.overview', true), 'icon' => 'fas fa-envelope']];

        if ($this->allows($package, 'client_management')) {
            $tabs['tabClientMailboxes'] = ['name' => Language::_('NamecraneMail.tabs.mailboxes', true), 'icon' => 'fas fa-inbox'];
            $tabs['tabClientAliases'] = ['name' => Language::_('NamecraneMail.tabs.aliases', true), 'icon' => 'fas fa-share'];
            $tabs['tabClientDomainAliases'] = ['name' => Language::_('NamecraneMail.tabs.domain_aliases', true), 'icon' => 'fas fa-globe'];
        }

        return $tabs;
    }

    /**
     * The domain for staff: status, usage, features, password-free sign-in, DNS records, and
     * the MXFilter panel when the package allows it.
     */
    public function tabAdminOverview($package, $service, ?array $get = null, ?array $post = null, ?array $files = null)
    {
        if (($post['op'] ?? null) === 'mxfilter_login') {
            return $this->mxfilterLogin($package, $service);
        }

        $view = $this->view('tab_admin_overview');
        $view->set('domain', $this->fetchDomain($service));
        $view->set('mxfilter_login', $this->allows($package, 'mxfilter_sso'));
        $view->set('tab_url', $this->adminTabUrl($service, 'tabAdminOverview'));

        return $view->fetch();
    }

    /**
     * The client's overview: the domain's status (and verification record while it waits),
     * webmail and MXFilter, usage, features, sign-in details and DNS records.
     */
    public function tabClientOverview($package, $service, ?array $get = null, ?array $post = null, ?array $files = null)
    {
        if (($post['op'] ?? null) === 'mxfilter_login') {
            return $this->mxfilterLogin($package, $service);
        }

        $domain = $this->fetchDomain($service);
        $view = $this->clientView('tab_client_overview', $package, $service, 'tabClientOverview');
        $view->set('domain', $domain);
        $view->set('login_url', $domain ? $this->loginUrl($service, $domain) : null);
        $view->set('mxfilter_login', $domain && $this->allows($package, 'mxfilter_sso') && !empty($domain['mxfilter_enabled'])
            && !empty($domain['mxfilter_sso']) && $this->usable($domain));
        $view->set('service_fields', $this->serviceFieldsToObject($service->fields));

        return $view->fetch();
    }

    public function tabClientMailboxes($package, $service, ?array $get = null, ?array $post = null, ?array $files = null)
    {
        if (!$this->allows($package, 'client_management')) {
            return '';
        }

        $post = (array) $post;
        $op = (string) ($post['op'] ?? '');

        if ($op === 'webmail') {
            return $this->webmailLogin($package, $service, (string) ($post['mailbox'] ?? ''));
        }

        $failed = $op !== '' && !$this->change($service, 'mailboxes', $op, $post);
        $domain = $this->fetchDomain($service);
        $list = $this->listing($service, $domain, 'users');
        $wanted = strtolower($failed && $op === 'update' ? (string) ($post['original'] ?? '') : ($op === '' ? (string) ($get['edit'] ?? '') : ''));
        $editing = null;

        foreach ($list['items'] as $user) {
            if (strtolower($user['username']) === $wanted) {
                $editing = $user;
            }
        }

        $posted = $failed && in_array($op, ['create', 'update'], true);

        $view = $this->clientView('tab_client_mailboxes', $package, $service, 'tabClientMailboxes');
        $view->set('domain', $domain);
        $view->set('mailboxes', $list['items']);
        $view->set('list_error', $list['error']);
        $view->set('editing', $editing);
        $view->set('form', [
            'name' => $editing ? $editing['username'] : ($posted ? trim((string) ($post['name'] ?? '')) : ''),
            'display_name' => $posted ? (string) ($post['display_name'] ?? '') : ($editing['display_name'] ?? ''),
            'password' => $posted && !$editing ? (string) ($post['password'] ?? '') : '',
            'disk_limit' => $posted ? (int) ($post['disk_limit'] ?? 0) : (int) ($editing['disk_limit'] ?? 0),
            'admin' => $posted ? !empty($post['admin']) : !empty($editing['admin']),
            'enabled' => $posted ? !empty($post['enabled']) : ($editing ? !empty($editing['enabled']) : true),
        ]);
        $view->set('webmail_login', $domain && $this->allows($package, 'webmail_sso') && !empty($domain['webmail_sso']));

        return $view->fetch();
    }

    public function tabClientAliases($package, $service, ?array $get = null, ?array $post = null, ?array $files = null)
    {
        if (!$this->allows($package, 'client_management')) {
            return '';
        }

        $post = (array) $post;
        $op = (string) ($post['op'] ?? '');
        $failed = $op !== '' && !$this->change($service, 'aliases', $op, $post);
        $domain = $this->fetchDomain($service);
        $list = $this->listing($service, $domain, 'aliases');
        $wanted = strtolower($failed && $op === 'update' ? (string) ($post['original'] ?? '') : ($op === '' ? (string) ($get['edit'] ?? '') : ''));
        $editing = null;

        foreach ($list['items'] as $alias) {
            if (strtolower($alias['username']) === $wanted) {
                $editing = $alias;
            }
        }

        $posted = $failed && in_array($op, ['create', 'update'], true);
        $features = [];

        foreach (self::ALIAS_FEATURES as $feature) {
            $features[$feature] = $posted ? !empty($post['feature_' . $feature]) : ($editing ? !empty($editing['features'][$feature]) : $feature === 'show_in_gal');
        }

        $view = $this->clientView('tab_client_aliases', $package, $service, 'tabClientAliases');
        $view->set('domain', $domain);
        $view->set('aliases', $list['items']);
        $view->set('list_error', $list['error']);
        $view->set('editing', $editing);
        $view->set('form', [
            'name' => $posted ? trim((string) ($post['name'] ?? '')) : ($editing['username'] ?? ''),
            'display_name' => $posted ? (string) ($post['display_name'] ?? '') : ($editing['display_name'] ?? ''),
            'targets' => $posted ? (string) ($post['targets'] ?? '') : implode("\n", $editing['targets'] ?? []),
            'features' => $features,
        ]);
        $view->set('features', self::ALIAS_FEATURES);

        return $view->fetch();
    }

    public function tabClientDomainAliases($package, $service, ?array $get = null, ?array $post = null, ?array $files = null)
    {
        if (!$this->allows($package, 'client_management')) {
            return '';
        }

        $post = (array) $post;
        $op = (string) ($post['op'] ?? '');
        $failed = $op !== '' && !$this->change($service, 'domainaliases', $op, $post);
        $domain = $this->fetchDomain($service);
        $list = $this->listing($service, $domain, 'domain-aliases');

        $view = $this->clientView('tab_client_domain_aliases', $package, $service, 'tabClientDomainAliases');
        $view->set('domain', $domain);
        $view->set('domain_aliases', $list['items']);
        $view->set('list_error', $list['error']);
        $view->set('form', ['name' => $failed && $op === 'create' ? trim((string) ($post['name'] ?? '')) : '']);

        return $view->fetch();
    }

    /**
     * Runs a client's change from one of the management tabs. A change that worked says so; one
     * that didn't sets the API's reason, and the tab shows its form again with what was typed.
     */
    private function change($service, string $page, string $op, array $post): bool
    {
        if (!($row = $this->row($service))) {
            return false;
        }

        $api = $this->api($row);
        $domain = $this->domainOf($service);
        $path = 'cranemail/domains/' . NamecraneMailApi::segment($domain);
        $name = trim((string) ($post['name'] ?? ''));
        $original = NamecraneMailApi::segment((string) ($post['original'] ?? ''));

        try {
            switch ($page . '.' . $op) {
                case 'mailboxes.create':
                    $api->post($path . '/users', [
                        'username' => $name,
                        'password' => (string) ($post['password'] ?? ''),
                        'display_name' => trim((string) ($post['display_name'] ?? '')),
                        'disk_limit' => (int) ($post['disk_limit'] ?? 0),
                        'admin' => !empty($post['admin']),
                    ]);
                    $notice = Language::_('NamecraneMail.!success.mailbox_added', true, $name . '@' . $domain);
                    break;

                case 'mailboxes.update':
                    $changes = [
                        'display_name' => trim((string) ($post['display_name'] ?? '')),
                        'disk_limit' => (int) ($post['disk_limit'] ?? 0),
                        'admin' => !empty($post['admin']),
                        'enabled' => !empty($post['enabled']),
                    ];
                    if ((string) ($post['password'] ?? '') !== '') {
                        $changes['password'] = (string) $post['password'];
                    }
                    $api->put($path . '/users/' . $original, $changes);
                    $notice = Language::_('NamecraneMail.!success.mailbox_saved', true);
                    break;

                case 'mailboxes.delete':
                    $api->delete($path . '/users/' . $original);
                    $notice = Language::_('NamecraneMail.!success.mailbox_deleted', true);
                    break;

                case 'aliases.create':
                case 'aliases.update':
                    $features = [];
                    foreach (self::ALIAS_FEATURES as $feature) {
                        $features[$feature] = !empty($post['feature_' . $feature]);
                    }
                    $alias = [
                        'username' => $name,
                        'display_name' => trim((string) ($post['display_name'] ?? '')),
                        'targets' => array_values(array_filter(array_map('trim', preg_split('/[\s,;]+/', (string) ($post['targets'] ?? ''))))),
                        'features' => $features,
                    ];
                    if ($op === 'create') {
                        $api->post($path . '/aliases', $alias);
                        $notice = Language::_('NamecraneMail.!success.alias_added', true, $name . '@' . $domain);
                    } else {
                        $api->put($path . '/aliases/' . $original, $alias);
                        $notice = Language::_('NamecraneMail.!success.alias_saved', true);
                    }
                    break;

                case 'aliases.delete':
                    $api->delete($path . '/aliases/' . $original);
                    $notice = Language::_('NamecraneMail.!success.alias_deleted', true);
                    break;

                case 'domainaliases.create':
                    $api->post($path . '/domain-aliases', ['alias' => strtolower($name)]);
                    $notice = Language::_('NamecraneMail.!success.domain_alias_added', true, strtolower($name));
                    break;

                case 'domainaliases.delete':
                    $api->delete($path . '/domain-aliases/' . $original);
                    $notice = Language::_('NamecraneMail.!success.domain_alias_removed', true);
                    break;

                default:
                    return false;
            }
        } catch (NamecraneMailApiException $e) {
            $this->apiError($e);

            return false;
        }

        $this->setMessage('success', $notice);

        return true;
    }

    /**
     * Opens a mailbox's webmail in the browser without its password, when the package allows it.
     */
    private function webmailLogin($package, $service, string $mailbox): string
    {
        if (!$this->allows($package, 'webmail_sso')) {
            $this->Input->setErrors(['webmail' => ['off' => Language::_('NamecraneMail.!error.webmail_sso.off', true)]]);

            return '';
        }

        return $this->signIn($service, '/users/' . NamecraneMailApi::segment($mailbox) . '/login', 'NamecraneMail.redirect.webmail');
    }

    /**
     * Opens the domain's MXFilter panel without a password, when the package allows it.
     */
    private function mxfilterLogin($package, $service): string
    {
        if (!$this->allows($package, 'mxfilter_sso')) {
            $this->Input->setErrors(['mxfilter' => ['off' => Language::_('NamecraneMail.!error.mxfilter_sso.off', true)]]);

            return '';
        }

        return $this->signIn($service, '/mxfilter/login', 'NamecraneMail.redirect.mxfilter');
    }

    /**
     * A page that sends the browser on to a one-time sign-in address from the API.
     */
    private function signIn($service, string $suffix, string $label): string
    {
        if (!($row = $this->row($service))) {
            return '';
        }

        try {
            $login = $this->api($row)->post('cranemail/domains/' . NamecraneMailApi::segment($this->domainOf($service)) . $suffix);
        } catch (NamecraneMailApiException $e) {
            $this->apiError($e);

            return '';
        }

        $url = (string) ($login['url'] ?? '');

        if (strpos($url, 'https://') !== 0) {
            $this->Input->setErrors(['sso' => ['url' => Language::_('NamecraneMail.!error.sso.url', true)]]);

            return '';
        }

        $view = $this->view('redirect');
        $view->set('url', $url);
        $view->set('label', Language::_($label, true));

        return $view->fetch();
    }

    /**
     * The domain from the API, or null with the reason set.
     */
    private function fetchDomain($service): ?array
    {
        if (!($row = $this->row($service))) {
            return null;
        }

        try {
            return $this->api($row)->get('cranemail/domains/' . NamecraneMailApi::segment($this->domainOf($service)));
        } catch (NamecraneMailApiException $e) {
            $this->apiError($e);

            return null;
        }
    }

    /**
     * One of the domain's lists (users, aliases, domain-aliases), or why it can't be shown yet.
     *
     * @return array{items: array, error: string|null}
     */
    private function listing($service, ?array $domain, string $what): array
    {
        if (!$domain) {
            return ['items' => [], 'error' => null];
        }

        if (!$this->usable($domain)) {
            return ['items' => [], 'error' => Language::_('NamecraneMail.manage_when_active', true)];
        }

        try {
            $result = $this->api($this->row($service))->get('cranemail/domains/' . NamecraneMailApi::segment($domain['domain']) . '/' . $what);
        } catch (NamecraneMailApiException $e) {
            return ['items' => [], 'error' => $e->getMessage()];
        }

        return ['items' => $what === 'users' ? ($result['users'] ?? []) : $result, 'error' => null];
    }

    /**
     * Whether the domain's mailboxes can be used and managed: verified and active.
     */
    private function usable(array $domain): bool
    {
        return !empty($domain['verified']) && ($domain['status'] ?? null) === 'active';
    }

    /**
     * The webmail address: the reseller's own login hostname when they've set one, else workspace.org.
     */
    private function loginUrl($service, array $domain): string
    {
        $host = 'workspace.org';

        try {
            foreach ($this->api($this->row($service))->get('cranemail/services') as $plan) {
                if ((int) $plan['id'] === (int) ($domain['service_id'] ?? 0) && !empty($plan['login_hostnames'])) {
                    $host = $plan['login_hostnames'][0];
                }
            }
        } catch (NamecraneMailApiException $e) {
            // The sign-in address falls back to workspace.org.
        }

        return 'https://' . $host;
    }

    // Plumbing

    /**
     * The service's server (or the one being provisioned on), or null with the error set.
     */
    private function row($service = null)
    {
        $row = $this->getModuleRow($service->module_row_id ?? null);

        if (!$row) {
            $this->Input->setErrors(['module_row' => ['missing' => Language::_('NamecraneMail.!error.module_row.missing', true)]]);
        }

        return $row ?: null;
    }

    private function api($row): NamecraneMailApi
    {
        return $this->client((string) ($row->meta->api_key ?? ''));
    }

    private function client(string $api_key): NamecraneMailApi
    {
        return new NamecraneMailApi(
            $api_key,
            Configure::get('NamecraneMail.api_host'),
            'NameCrane-Blesta-Mail/' . $this->getVersion(),
            function (string $label, string $data, string $direction, bool $success) {
                $this->log($label, $data, $direction, $success);
            }
        );
    }

    /**
     * Sets the API's refusal as the error, with what to do about it when the server is the cause.
     */
    private function apiError(NamecraneMailApiException $e): void
    {
        $body = $e->body();

        if ($e->status() === 422 && !empty($body['services'])) {
            $message = Language::_('NamecraneMail.!error.service_id.needed', true, $this->describeServices($body['services']));
        } elseif ($e->status() === 401) {
            $message = Language::_('NamecraneMail.!error.api_key.refused', true, $e->getMessage());
        } else {
            $message = $e->getMessage();
        }

        $this->Input->setErrors(['api' => ['response' => $message]]);
    }

    private function describeServices(array $services): string
    {
        return implode(', ', array_map(function ($service) {
            return '#' . $service['id'] . ' ' . $service['name'];
        }, $services));
    }

    private function domainOf($service): string
    {
        return strtolower((string) ($this->serviceFieldsToObject($service->fields)->namecrane_mail_domain ?? ''));
    }

    private function adminTabUrl($service, string $tab): string
    {
        return $this->base_uri . 'clients/servicetab/' . $service->client_id . '/' . $service->id . '/' . $tab . '/';
    }

    private function clientTabUrl($service, string $tab): string
    {
        return $this->base_uri . 'services/manage/' . $service->id . '/' . $tab . '/';
    }

    /**
     * A client tab's view, with the tab bar's links and the stylesheet and script it loads.
     */
    private function clientView(string $name, $package, $service, string $tab): View
    {
        $tabs = [];

        foreach ($this->getClientTabs($package) as $method => $details) {
            $tabs[$method] = ['label' => $details['name'], 'url' => $this->clientTabUrl($service, $method)];
        }

        $view = $this->view($name);
        $view->set('tab', $tab);
        $view->set('tabs', $tabs);
        $view->set('tab_url', $this->clientTabUrl($service, $tab));
        $view->set('domain_name', $this->domainOf($service));
        $view->set('version', $this->getVersion());

        return $view;
    }

    private function view(string $name): View
    {
        $this->view = new View($name, 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        return $this->view;
    }
}
