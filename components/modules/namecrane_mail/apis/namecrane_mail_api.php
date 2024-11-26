<?php

use GuzzleHttp\Client;

class NamecraneMailApi {

  private $api_key;

  public function __construct($api_key) {
    $this->api_key = $api_key;
  }

  public function execute($method, $action, $post = []) {

    $guzzle = new Client();

    try {
  
      $return = $guzzle->request($method, 'https://namecrane.com/index.php?m=cranemail&action=api/' . $action, [
        'headers'     => [ 'X-API-KEY' => $this->api_key ],
        'form_params' => ($method == 'POST' ? $post : [])
      ])->getBody();
      
      $return = json_decode($return, true);
  
      if(json_last_error() !== JSON_ERROR_NONE) {
        return ['status' => false, 'message' => 'Invalid JSON response from Namecrane. Ticket support (and blame Fran)' ];
      }
  
      return [ 'status' => $return['status'], 'message' => $return['message'], 'data' => $return ];
  
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      return [ 'status' => false, 'message' => $e->getMessage() ];
    } catch(\GuzzleHttp\Exception\GuzzleException $e) {
      return [ 'status' => false, 'message' => $e->getMessage() ];
    }
  
  }

}
