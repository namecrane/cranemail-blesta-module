<?php

use Blesta\Core\Util\Validate\Server;

class NamecraneMail extends Module {

  public function __construct() {

    // Load the language required by this module
    Language::loadLang('namecrane_mail', null, dirname(__FILE__) . DS . 'language' . DS);

    // Load components required by this module
    Loader::loadComponents($this, ['Input']);

    // Load module config
    $this->loadConfig(dirname(__FILE__) . DS . 'config.json');

    Configure::load('namecrane_mail', dirname(__FILE__) . DS . 'config' . DS);

  }

  public function manageModule($module, array &$vars) {

    $this->view = new View('manage', 'default');
    $this->view->base_uri = $this->base_uri;
    $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);

    Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

    $this->view->set('module', $module);

    return $this->view->fetch();
  
  }

  public function manageAddRow(array &$vars) {

    $this->view = new View('add_row', 'default');
    $this->view->base_uri = $this->base_uri;
    $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);

    Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

    if (!empty($vars)) {

      $checkbox_fields = [ ];

      foreach ($checkbox_fields as $checkbox_field) {
        if (!isset($vars[$checkbox_field])) {
          $vars[$checkbox_field] = 'false';
        }
      }

    }

    $this->view->set('vars', (object) $vars);

    return $this->view->fetch();

  }

  public function manageEditRow($module_row, array &$vars) {

      $this->view = new View('edit_row', 'default');
      $this->view->base_uri = $this->base_uri;
      $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);

      Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

      if (empty($vars)) {
        $vars = $module_row->meta;
      } else {

        $checkbox_fields = [];

        foreach ($checkbox_fields as $checkbox_field) {
          if (!isset($vars[$checkbox_field])) {
            $vars[$checkbox_field] = 'false';
          }
        }

      }

    $this->view->set('vars', (object) $vars);

    return $this->view->fetch();

  }

  public function addModuleRow(array &$vars) {

    $meta_fields = [ 'server_name', 'api_key' ];
    $encrypted_fields = [];

    // Set unset checkboxes
    $checkbox_fields = [];

    foreach ($checkbox_fields as $checkbox_field) {
      if (!isset($vars[$checkbox_field])) {
        $vars[$checkbox_field] = 'false';
      }
    }

    $this->Input->setRules($this->getRowRules($vars));

    if ($this->Input->validates($vars)) {

      $meta = [];

      foreach ($vars as $key => $value) {

        if (in_array($key, $meta_fields)) {
          $meta[] = [
            'key'       => $key,
            'value'     => $value,
            'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
            ];
        }

      }

      return $meta;

    }
 
  }

  public function editModuleRow($module_row, array &$vars) {

    $meta_fields = ['server_name', 'api_key'];
    $encrypted_fields = [];

    $checkbox_fields = [];

    foreach ($checkbox_fields as $checkbox_field) {
      if (!isset($vars[$checkbox_field])) {
        $vars[$checkbox_field] = 'false';
      }
    }

    $this->Input->setRules($this->getRowRules($vars));

    if ($this->Input->validates($vars)) {

      $meta = [];

      foreach ($vars as $key => $value) {
        if (in_array($key, $meta_fields)) {
          $meta[] = [
            'key'       => $key,
            'value'     => $value,
            'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
          ];
        }
    
      }

      return $meta;

    }

  }

  private function getRowRules(&$vars) {

    $rules = [
      'server_name' => [
        'valid' => [
          'rule' => true,
          'message' => Language::_('NamecraneMail.!error.server_name.valid', true)
        ]
      ],
      'api_key' => [
        'valid' => [
          'rule' => true,
          'message' => Language::_('NamecraneMail.!error.api_key.valid', true)
        ]
      ]
    ];

    return $rules;

  }
  
  public function addPackage(array $vars = null) {

    $this->Input->setRules($this->getPackageRules($vars));

    $meta = [];

    if ($this->Input->validates($vars)) {

      if (!isset($vars['meta'] )) {
        return [];
      }

      foreach ($vars['meta'] as $key => $value) {
        $meta[] = [
          'key'       => $key,
          'value'     => $value,
          'encrypted' => 0
        ];
      }

    }

    return $meta;

  }

  public function editPackage($package, array $vars = null) {

    $this->Input->setRules($this->getPackageRules($vars));

    $meta = [];

    if ($this->Input->validates($vars)) {

      if (!isset($vars['meta'] )) {
        return [];
      }

      // Return all package meta fields
      foreach ($vars['meta'] as $key => $value) {
        $meta[] = [
          'key'       => $key,
          'value'     => $value,
          'encrypted' => 0
        ];
      }

    }

    return $meta;

  }

  private function getPackageRules(array $vars) {

    $rules = [
      'meta[disklimit]' => [
        'valid' => [
          'rule'    => [[ $this, 'validInteger' ]],
          'message' => Language::_('NamecraneMail.!error.disklimit.valid', true)
        ]
      ],
      'meta[userlimit]' => [
        'valid' => [
          'rule'    => [[ $this, 'validInteger' ]],
          'message' => Language::_('NamecraneMail.!error.userlimit.valid', true)
        ]
      ],
      'meta[useraliaslimit]' => [
        'valid' => [
        'rule'    => [[ $this, 'validInteger' ]],
        'message' => Language::_('NamecraneMail.!error.useraliaslimit.valid', true)
        ]
      ],
      'meta[domainaliaslimit]' => [
        'valid' => [
          'rule'    => [[ $this, 'validInteger' ]],
          'message' => Language::_('NamecraneMail.!error.domainaliaslimit.valid', true)
        ]
      ],
      'meta[spamexperts]' => [
        'valid' => [
          'rule'    => true,
          'message' => Language::_('NamecraneMail.!error.spamexperts.valid', true)
        ]
      ],
      'meta[archive_years]' => [
        'valid' => [
          'rule'    => [[ $this, 'validArchiveYears' ]],
          'message' => Language::_('NamecraneMail.!error.archive_years.valid', true)
        ]
      ],        
      'meta[archive_direction]' => [
        'valid' => [
          'rule'    => [ 'in_array', [ 'in', 'out', 'inout' ] ],
          'message' => Language::_('NamecraneMail.!error.archive_direction.valid', true)
        ]
      ]
    ];

    return $rules;

  }

  public function getPackageFields($vars = null) {

    Loader::loadHelpers($this, ['Html']);

    $fields = new ModuleFields();

    // disk space

    $disklimit = $fields->label(Language::_('NamecraneMail.package_fields.disklimit', true), 'namecrane_mail_disklimit');
    $disklimit->attach(
      $fields->fieldText(
        'meta[disklimit]',
        (isset($vars->meta['disklimit']) ? $vars->meta['disklimit'] : null),
        ['id' => 'namecrane_mail_disklimit']
      )
    );

    $fields->setField($disklimit);

    // email users

    $userlimit = $fields->label(Language::_('NamecraneMail.package_fields.userlimit', true), 'namecrane_mail_userlimit');
    $userlimit->attach(
      $fields->fieldText(
        'meta[userlimit]',
        (isset($vars->meta['userlimit']) ? $vars->meta['userlimit'] : null),
        ['id' => 'namecrane_mail_userlimit']
      )
    );

    $fields->setField($userlimit);

    // user aliases

    $useraliaslimit = $fields->label(Language::_('NamecraneMail.package_fields.useraliaslimit', true), 'namecrane_mail_useraliaslimit');
    $useraliaslimit->attach(
      $fields->fieldText(
        'meta[useraliaslimit]',
        (isset($vars->meta['useraliaslimit']) ? $vars->meta['useraliaslimit'] : null),
        ['id' => 'namecrane_mail_useraliaslimit']
      )
    );

    $fields->setField($useraliaslimit);

    // domain aliases

    $domainaliaslimit = $fields->label(Language::_('NamecraneMail.package_fields.domainaliaslimit', true), 'namecrane_mail_domainaliaslimit');
    $domainaliaslimit->attach(
      $fields->fieldText(
        'meta[domainaliaslimit]',
        (isset($vars->meta['domainaliaslimit']) ? $vars->meta['domainaliaslimit'] : null),
        ['id' => 'namecrane_mail_domainaliaslimit']
      )
    );

    $fields->setField($domainaliaslimit);

    // archive years 

    $archive_years = $fields->label(Language::_('NamecraneMail.package_fields.archive_years', true), 'namecrane_mail_archive_years');
    $archive_years->attach(
      $fields->fieldSelect(
        'meta[archive_years]',
        [
          '0' => 'Disabled',
          '1' => '1 Year',
          '2' => '2 Years',
          '3' => '3 Years',
          '4' => '4 Years',
          '5' => '5 Years',
          '6' => '6 Years',
          '7' => '7 Years',
          '8' => '8 Years',
          '9' => '9 Years',
          '10' => '10 Years',
          '15' => '15 Years',
          '20' => '20 Years'
        ],
        (isset($vars->meta['archive_years']) ? $vars->meta['archive_years'] : null) == 'true',
        ['id' => 'namecrane_mail_archive_years']
      )
    );

    $fields->setField($archive_years);
    
    // archiving direction

    $archive_direction = $fields->label(Language::_('NamecraneMail.package_fields.archive_direction', true), 'namecrane_mail_archive_direction');
    $archive_direction->attach(
      $fields->fieldSelect(
        'meta[archive_direction]',
        [
          'in'    => 'Incoming',
          'out'   => 'Outgoing',
          'inout' => 'Both'        
        ],
        (isset($vars->meta['archive_direction']) ? $vars->meta['archive_direction'] : null) == 'true',
        ['id' => 'namecrane_mail_archive_direction']
      )
    );

    $fields->setField($archive_direction);
    
    // spamexperts

    $spamexperts = $fields->label(Language::_('NamecraneMail.package_fields.spamexperts', true), 'namecrane_mail_spamexperts');
    $spamexperts->attach(
      $fields->fieldCheckbox(
        'meta[spamexperts]',
        'true',
        (isset($vars->meta['spamexperts']) ? $vars->meta['spamexperts'] : null) == 'true',
        ['id' => 'namecrane_mail_spamexperts']
      )
    );

    $fields->setField($spamexperts);

    // spamexperts admin access
    
    $spamexperts_adminaccess = $fields->label(Language::_('NamecraneMail.package_fields.spamexperts_adminaccess', true), 'namecrane_mail_spamexperts_adminaccess');
    $spamexperts_adminaccess->attach(
      $fields->fieldSelect(
        'meta[spamexperts_adminaccess]',
        [
          'primary' => 'Primary Administrator Only',
          'all'     => 'All Domain Administrators'
        ],
        (isset($vars->meta['spamexperts_adminaccess']) ? $vars->meta['spamexperts_adminaccess'] : null) == 'true',
        ['id' => 'namecrane_mail_spamexperts_adminaccess']
      )
    );

    $fields->setField($spamexperts_adminaccess);

    // file storage

    $filestorage = $fields->label(Language::_('NamecraneMail.package_fields.filestorage', true), 'namecrane_mail_filestorage');
    $filestorage->attach(
      $fields->fieldCheckbox(
        'meta[filestorage]',
        'true',
        (isset($vars->meta['filestorage']) ? $vars->meta['filestorage'] : null) == 'true',
        ['id' => 'namecrane_mail_filestorage']
      )
    );

    $fields->setField($filestorage);

    // office
    
    $office = $fields->label(Language::_('NamecraneMail.package_fields.office', true), 'namecrane_mail_office');
    $office->attach(
      $fields->fieldCheckbox(
        'meta[office]',
        'true',
        (isset($vars->meta['office']) ? $vars->meta['office'] : null) == 'true',
        ['id' => 'namecrane_mail_office']
      )
    );

    $fields->setField($office);

    return $fields;

  }

  public function addService($package, array $vars = null, $parent_package = null, $parent_service = null, $status = 'pending') {

    $this->validateService($package, $vars);

    if ($this->Input->errors()) {
      return;
    }

    if ($vars['use_module'] == 'true' && $row = $this->getModuleRow()) {

      $api = $this->getApi($row->meta->api_key);

      $post = [
        'domain'                  => $vars['domain'],
        'disklimit'               => (isset($vars['configoptions']['disklimit']) ? $vars['configoptions']['disklimit'] : $package->meta->disklimit),
        'userlimit'               => (isset($vars['configoptions']['userlimit']) ? $vars['configoptions']['userlimit'] : $package->meta->userlimit),
        'useraliaslimit'          => (isset($vars['configoptions']['useraliaslimit']) ? $vars['configoptions']['useraliaslimit'] : $package->meta->useraliaslimit),
        'spamexperts'             => (isset($vars['configoptions']['spamexperts']) ? $vars['configoptions']['spamexperts'] : $package->meta->spamexperts),
        'spamexperts_adminaccess' => (isset($vars['configoptions']['spamexperts_adminaccess']) ? $vars['configoptions']['spamexperts_adminaccess'] : $package->meta->spamexperts_adminaccess),
        'domainaliaslimit'        => (isset($vars['configoptions']['domainaliaslimit']) ? $vars['configoptions']['domainaliaslimit'] : $package->meta->domainaliaslimit),
        'archive_years'           => (isset($vars['configoptions']['archive_years']) ? $vars['configoptions']['archive_years'] : $package->meta->archive_years),
        'archive_direction'       => (isset($vars['configoptions']['archive_direction']) ? $vars['configoptions']['archive_direction'] : $package->meta->archive_direction),
        'filestorage'             => (isset($vars['configoptions']['filestorage']) ? $vars['configoptions']['filestorage'] : $package->meta->filestorage),
        'office'                  => (isset($vars['configoptions']['office']) ? $vars['configoptions']['office'] : $package->meta->office)
      ];

      $return = $api->execute('POST', 'domain/create', $post);

      if(!$return['status']) {
       
        $this->Input->SetErrors([ 'api' => [ 'response' => $return['message'] ] ]);

        return null;

      }

      return [
        [
          'key'       => 'domain',
          'value'     => $vars['domain'],
          'encrypted' => 0
        ],
        [
          'key'       => 'username',
          'value'     => $return['data']['username'],
          'encrypted' => 0
        ],
        [
          'key'       => 'password',
          'value'     => $return['data']['password'],
          'encrypted' => 1
        ]
      ];

    }

  }

  public function editService($package, $service, array $vars = null, $parent_package = null, $parent_service = null) {

    $this->validateService($package, $vars);

    if ($this->Input->errors()) {
      return;
    }

    if ($vars['use_module'] == 'true' && $row = $this->getModuleRow()) {
      
      $api = $this->getApi($row->meta->api_key);

      $post = [
        'domain'                  => $vars['domain'],
        'disklimit'               => (isset($vars['configoptions']['disklimit']) ? $vars['configoptions']['disklimit'] : $package->meta->disklimit),
        'userlimit'               => (isset($vars['configoptions']['userlimit']) ? $vars['configoptions']['userlimit'] : $package->meta->userlimit),
        'useraliaslimit'          => (isset($vars['configoptions']['useraliaslimit']) ? $vars['configoptions']['useraliaslimit'] : $package->meta->useraliaslimit),
        'spamexperts'             => (isset($vars['configoptions']['spamexperts']) ? (bool)$vars['configoptions']['spamexperts'] : (bool)$package->meta->spamexperts),
        'spamexperts_adminaccess' => (isset($vars['configoptions']['spamexperts_adminaccess']) ? $vars['configoptions']['spamexperts_adminaccess'] : $package->meta->spamexperts_adminaccess),
        'domainaliaslimit'        => (isset($vars['configoptions']['domainaliaslimit']) ? $vars['configoptions']['domainaliaslimit'] : $package->meta->domainaliaslimit),
        'archive_years'           => (isset($vars['configoptions']['archive_years']) ? $vars['configoptions']['archive_years'] : $package->meta->archive_years),
        'archive_direction'       => (isset($vars['configoptions']['archive_direction']) ? $vars['configoptions']['archive_direction'] : $package->meta->archive_direction),
        'filestorage'             => (isset($vars['configoptions']['filestorage']) ? $vars['configoptions']['filestorage'] : $package->meta->filestorage),
        'office'                  => (isset($vars['configoptions']['office']) ? $vars['configoptions']['office'] : $package->meta->office)
      ];

      $return = $api->execute('POST', 'domain/modify', $post);

      if(!$return['status']) {
        $this->Input->SetErrors([ 'api' => [ 'response' => $return['message'] ] ]);
      }

      return null;

    }

  }

  public function suspendService($package, $service, $parent_package = null, $parent_service = null) {

    if (($row = $this->getModuleRow())) {

      $service_fields = $this->serviceFieldsToObject($service->fields);

      $api = $this->getApi($row->meta->api_key);

      $post = [
        'domain'  => $service_fields->domain
      ];
    
      $return = $api->execute('POST', 'domain/suspend', $post);
      
      if(!$return['status']) {
        $this->Input->SetErrors([ 'api' => [ 'response' => $return['message'] ] ]);
      }

    }

    return null;

  }


  public function unsuspendService($package, $service, $parent_package = null, $parent_service = null) {

    if (($row = $this->getModuleRow())) {

      $service_fields = $this->serviceFieldsToObject($service->fields);

      $api = $this->getApi($row->meta->api_key);

      $post = [
        'domain'  => $service_fields->domain
      ];
    
      $return = $api->execute('POST', 'domain/unsuspend', $post);
      
      if(!$return['status']) {
        $this->Input->SetErrors([ 'api' => [ 'response' => $return['message'] ] ]);
      }

    }

    return null;

  }

  public function cancelService($package, $service, $parent_package = null, $parent_service = null) {

    if (($row = $this->getModuleRow())) {

      $service_fields = $this->serviceFieldsToObject($service->fields);

      $api = $this->getApi($row->meta->api_key);

      $post = [
        'domain'  => $service_fields->domain
      ];
    
      $return = $api->execute('POST', 'domain/delete', $post);
      
      if(!$return['status']) {
        $this->Input->SetErrors([ 'api' => [ 'response' => $return['message'] ] ]);
      }

    }

    return null;

  }

  public function validateService($package, array $vars = null) {
      $this->Input->setRules($this->getServiceRules($vars));
      return $this->Input->validates($vars);
  }

  public function validateServiceEdit($service, array $vars = null) {
    $this->Input->setRules($this->getServiceRules($vars, true));
    return $this->Input->validates($vars);
  }

  private function getServiceRules(array $vars = null, $edit = false) {

    $rules = [
      'domain' => [
        'valid' => [
          'if_set'  => $edit,
          'rule'    => [[ $this, 'validateDomain' ]],
          'message' => Language::_('NamecraneMail.!error.domain.valid', true)
        ]
      ],
      'username' => [
        'valid' => [
          'if_set'  => $edit,
          'rule'    => true,
          'message' => Language::_('NamecraneMail.!error.username.valid', true)
        ]
      ],
      'password' => [
        'valid' => [
          'if_set'  => $edit,
          'rule'    => true,
          'message' => Language::_('NamecraneMail.!error.password.valid', true)
        ]
      ]
    ];

    // Unset irrelevant rules when editing a service
    if ($edit) {

      $edit_fields = [];

      foreach ($rules as $field => $rule) {
        if (!in_array($field, $edit_fields)) {
          unset($rules[$field]);
        }
      }

    }

    return $rules;

  }

  public function changeServicePackage($package_from, $package_to, $service, $parent_package = null, $parent_service = null) {

    $service_fields = $this->serviceFieldsToObject($service->fields);

    $vars = [
      'domain'      => $service_fields->domain,
      'use_module'  => true
    ];

    return $this->editService($package_to, $service, $vars, $parent_package, $parent_service);

  }

  private function getApi($api_key) {

      Loader::load(dirname(__FILE__) . DS . 'apis' . DS . 'namecrane_mail_api.php');

      $api = new NamecraneMailApi($api_key);

      return $api;

  }

  public function getAdminServiceInfo($service, $package) {
    return $this->getResourceUsage($package, $service);
  }

  public function getClientServiceInfo($service, $package) {
    return $this->getResourceUsage($package, $service);
  }

  public function getAdminAddFields($package, $vars = null) {

    Loader::loadHelpers($this, ['Html']);

    $fields = new ModuleFields();

    $domain = $fields->label(Language::_('NamecraneMail.service_fields.domain', true), 'namecrane_mail_domain');

    $domain->attach(
      $fields->fieldText(
        'domain',
        (isset($vars->domain) ? $vars->domain : null),
        [ 'id' => 'namecrane_mail_domain' ]
      )
    );
    
    $fields->setField($domain);

    return $fields;

  }

  public function getAdminEditFields($package, $vars = null) {

    Loader::loadHelpers($this, ['Html']);

    $fields = new ModuleFields();

    $domain = $fields->label(Language::_('NamecraneMail.service_fields.domain', true), 'namecrane_mail_domain');

    $domain->attach(
      $fields->fieldText(
        'domain',
        (isset($vars->domain) ? $vars->domain : null),
        [ 'id' => 'namecrane_mail_domain' ]
      )
    );

    $username = $fields->label(Language::_('NamecraneMail.service_fields.username', true), 'namecrane_mail_username');

    $username->attach(
      $fields->fieldText(
        'username',
        (isset($vars->username) ? $vars->username : null),
        [ 'id' => 'namecrane_mail_username' ]
      )
    );

    $password = $fields->label(Language::_('NamecraneMail.service_fields.password', true), 'namecrane_mail_password');

    $password->attach(
      $fields->fieldText(
        'password',
        (isset($vars->password) ? $vars->password : null),
        [ 'id' => 'namecrane_mail_password' ]
      )
    );

    $fields->setField($domain);
    $fields->setField($username);
    $fields->setField($password);

    return $fields;

  }

  public function getClientAddFields($package, $vars = null) {

    Loader::loadHelpers($this, ['Html']);

    $fields = new ModuleFields();

    $domain = $fields->label(Language::_('NamecraneMail.service_fields.domain', true), 'namecrane_mail_domain');
    $domain->attach(
      $fields->fieldText(
        'domain',
        (isset($vars->domain) ? $vars->domain : null),
        ['id' => 'namecrane_mail_domain']
      )
    );

    $fields->setField($domain);

    return $fields;

  }

  public function getClientEditFields($package, $vars = null) {

    Loader::loadHelpers($this, ['Html']);

    $fields = new ModuleFields();

    // Set the Domain field
    $domain = $fields->label(Language::_('NamecraneMail.service_fields.domain', true), 'namecrane_mail_domain');
    $domain->attach(
      $fields->fieldText(
        'domain',
        (isset($vars->domain) ? $vars->domain : null),
        ['id' => 'namecrane_mail_domain']
      )
    );

    $fields->setField($domain);

    return $fields;

  }

  // interface tabs

  public function getClientTabs($package) {

    $tabs = [
      'getResourceUsage'  => Language::_('NamecraneMail.tabs.resource_usage', true),
      'getDNSSettings'    => Language::_('NamecraneMail.tabs.dns_records', true),
      'webmailLogin'      => Language::_('NamecraneMail.tabs.webmail_login', true),
    ];

    if($package->meta->spamexperts) {
      $tabs['getSpamExpertsSSO'] = Language::_('NamecraneMail.tabs.login_to_spamexperts', true);
    }

    return $tabs;

  }

  public function getAdminTabs($package) {

    $tabs = [
      'getResourceUsage'  => Language::_('NamecraneMail.tabs.resource_usage', true),
      'getDNSSettings'    => Language::_('NamecraneMail.tabs.dns_records', true)
    ];

    if($package->meta->spamexperts) {
      $tabs['getSpamExpertsSSO'] = Language::_('NamecraneMail.tabs.login_to_spamexperts', true);
    }

    return $tabs;
    
  }

  public function getResourceUsage($package, $service, array $get = null, array $post = null, array $files = null) {

    $row            = $this->getModuleRow();
    $service_fields = $this->serviceFieldsToObject($service->fields);

    $api = $this->getApi($row->meta->api_key);

    $post = [
      'domain' => $service_fields->domain
    ];
  
    $stats = $api->execute('POST', 'domain/info', $post);

    // handle loading our view
    
    $this->view = new View('resource_usage', 'default');
    $this->view->base_uri = $this->base_uri;
    $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);

    Loader::loadHelpers($this, ['Form', 'Html']);

    if(!empty($stats['data']['data']['spamexperts_direction'])) {

    } else {
      $stats['data']['data']['spamexperts_direction'] = Language::_('NamecraneMail.tabs.webmail_login', true);
    }    

    $this->view->set('info', $stats['data']['data']);
    
    return $this->view->fetch();
    
  }

  public function getDNSSettings($package, $service, array $get = null, array $post = null, array $files = null) {

    $row            = $this->getModuleRow();
    $service_fields = $this->serviceFieldsToObject($service->fields);

    $api = $this->getApi($row->meta->api_key);

    $post = [
      'domain' => $service_fields->domain
    ];
  
    $stats = $api->execute('POST', 'domain/info', $post);
    
    $this->view = new View('dns_records', 'default');
    $this->view->base_uri = $this->base_uri;
    $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);

    Loader::loadHelpers($this, ['Form', 'Html']);

    $this->view->set('dns', $stats['data']['data']['dns']);
    $this->view->set('dkim', $stats['data']['data']['dkim']);
    $this->view->set('service_fields', $service_fields);

    return $this->view->fetch();

  }

  public function getSpamExpertsSSO($package, $service, array $get = null, array $post = null, array $files = null) {

    $row            = $this->getModuleRow();
    $service_fields = $this->serviceFieldsToObject($service->fields);

    $api = $this->getApi($row->meta->api_key);

    $post = [
      'domain' => $service_fields->domain
    ];
  
    $sso = $api->execute('POST', 'spamexperts/login', $post);

    if(!$sso['status']) {
      return 'Couldn\'t get authentication token.';
    }
  
    // client side is fine raw dogging a redirect header, but admin side scoffs at it
    // soooo, we have to do it in javascript like a dengenerate

    $this->view = new View('spamexperts_sso', 'default');
    $this->view->base_uri = $this->base_uri;
    $this->view->setDefaultView('components' . DS . 'modules' . DS . 'namecrane_mail' . DS);
    
    Loader::loadHelpers($this, [ 'Form', 'Html' ]);

    $this->view->set('sso', $sso['data']);

    return $this->view->fetch();

  }

  public function webmailLogin($package, $service, array $get = null, array $post = null, array $files = null) {

    header('Location: https://workspace.org/');

    exit();

  }


  // Validation

  public function validInteger($value) {

    if(filter_var($value, FILTER_VALIDATE_INT, [ 'options' =>  [ 'min_range' => 0 ] ]) === false) {
      return false;
    }

    return true;

  }

  public function validateDomain($value) {

    if(filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
      return false;
    }

    return true;

  }
  
  public function validArchiveYears($value) {
    return in_array($value, [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20 ]);
  }

}
