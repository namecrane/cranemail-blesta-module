<?php

// Basics
$lang['NamecraneMail.name'] = 'Namecrane Mail';
$lang['NamecraneMail.description'] = 'Your Brother in Crane, helping you resell mail services.';
$lang['NamecraneMail.module_row'] = 'Server';
$lang['NamecraneMail.module_row_plural'] = 'Servers';
$lang['NamecraneMail.module_group'] = 'Server Group';


// Module management
$lang['NamecraneMail.add_module_row'] = 'Add Server';
$lang['NamecraneMail.add_module_group'] = 'Add Server Group';
$lang['NamecraneMail.manage.module_rows_title'] = 'Servers';

$lang['NamecraneMail.manage.module_rows_heading.server_name'] = 'Server Name';
$lang['NamecraneMail.manage.module_rows_heading.api_key'] = 'API Key';
$lang['NamecraneMail.manage.module_rows_heading.options'] = 'Options';
$lang['NamecraneMail.manage.module_rows.edit'] = 'Edit';
$lang['NamecraneMail.manage.module_rows.delete'] = 'Delete';
$lang['NamecraneMail.manage.module_rows.confirm_delete'] = 'Are you sure you want to delete this Server';

$lang['NamecraneMail.manage.module_rows_no_results'] = 'There are no Servers.';

$lang['NamecraneMail.manage.module_groups_title'] = 'Groups';
$lang['NamecraneMail.manage.module_groups_heading.name'] = 'Name';
$lang['NamecraneMail.manage.module_groups_heading.module_rows'] = 'Servers';
$lang['NamecraneMail.manage.module_groups_heading.options'] = 'Options';

$lang['NamecraneMail.manage.module_groups.edit'] = 'Edit';
$lang['NamecraneMail.manage.module_groups.delete'] = 'Delete';
$lang['NamecraneMail.manage.module_groups.confirm_delete'] = 'Are you sure you want to delete this Server';

$lang['NamecraneMail.manage.module_groups.no_results'] = 'There is no Server Group';

// Tabs
$lang['NamecraneMail.tabs.resource_usage'] = 'Resource Usage';
$lang['NamecraneMail.tabs.dns_records'] = 'DNS Records';
$lang['NamecraneMail.tabs.login_to_spamexperts'] = 'Login to SpamExperts';
$lang['NamecraneMail.tabs.login_details'] = 'Login Details';

// Add row
$lang['NamecraneMail.add_row.box_title'] = 'Namecrane Mail - Add Server';
$lang['NamecraneMail.add_row.add_btn'] = 'Add Server';


// Edit row
$lang['NamecraneMail.edit_row.box_title'] = 'Namecrane Mail - Edit Server';
$lang['NamecraneMail.edit_row.edit_btn'] = 'Update Server';


// Row meta
$lang['NamecraneMail.row_meta.server_name'] = 'Server Name';
$lang['NamecraneMail.row_meta.api_key'] = 'API Key';


$lang['NamecraneMail.row_meta.tooltip.server_name'] = 'A name for this service.';
$lang['NamecraneMail.row_meta.tooltip.api_key'] = 'API Key found inside of the Namecrane Mail Portal.';


// Errors
$lang['NamecraneMail.!error.server_name.valid'] = 'Invalid Server Name';
$lang['NamecraneMail.!error.api_key.valid'] = 'Invalid API Key';
$lang['NamecraneMail.!error.domain.valid'] = 'Invalid Domain';
$lang['NamecraneMail.!error.module_row.missing'] = 'An internal error occurred. The module row is unavailable.';
$lang['NamecraneMail.!error.disklimit.valid'] = 'Disk Space must be a positive whole integer.';
$lang['NamecraneMail.!error.userlimit.valid'] = 'Max Email Users be a positive whole integer.';
$lang['NamecraneMail.!error.useraliaseslimit.valid'] = 'Max User Aliases must be a positive whole integer.';
$lang['NamecraneMail.!error.domainaliaseslimit.valid'] = 'Max Domain Aliases must be a positive whole integer.';
$lang['NamecraneMail.!error.archive_direction.valid'] = 'Archive Direction must be one of "in", "out", or "inout".';

// Service info
$lang['NamecraneMail.service_info.domain'] = 'Domain';
$lang['NamecraneMail.service_info.username'] = 'Username';
$lang['NamecraneMail.service_info.password'] = 'Password';

////// These are the definitions for if you are trying to include a login link in the service info pages
////$lang['NamecraneMail.service_info.options'] = 'Options';
////$lang['NamecraneMail.service_info.option_login'] = 'Login';

// Service Fields
$lang['NamecraneMail.service_fields.domain'] = 'Domain';
$lang['NamecraneMail.service_fields.username'] = 'Username';
$lang['NamecraneMail.service_fields.password'] = 'Password';

// Package Fields
$lang['NamecraneMail.package_fields.disklimit'] = 'Disk Space (GB)';
$lang['NamecraneMail.package_fields.userlimit'] = 'Max Email Users';
$lang['NamecraneMail.package_fields.useraliaslimit'] = 'Max User Aliases';
$lang['NamecraneMail.package_fields.domainaliaslimit'] = 'Max Domain Aliases';
$lang['NamecraneMail.package_fields.spamexperts'] = 'SpamExperts';
$lang['NamecraneMail.package_fields.spamexperts_adminaccess'] = 'SpamExperts Administrator Access';
$lang['NamecraneMail.package_fields.archive_years'] = 'Archive Years';
$lang['NamecraneMail.package_fields.archive_direction'] = 'Archive Direction';
$lang['NamecraneMail.package_fields.filestorage'] = 'File Storage';
$lang['NamecraneMail.package_fields.office'] = 'Office Suite';

$lang['NamecraneMail.resource_usage.diskspace'] = 'Disk Space';
$lang['NamecraneMail.resource_usage.email_users'] = 'Email Users';
$lang['NamecraneMail.resource_usage.email_aliases'] = 'Email Aliases';
$lang['NamecraneMail.resource_usage.domain_aliases'] = 'Domain Aliases';
$lang['NamecraneMail.resource_usage.filestorage'] = 'File Storage';
$lang['NamecraneMail.resource_usage.office'] = 'Office Suite';
$lang['NamecraneMail.resource_usage.spamexperts.status'] = 'SpamExperts';
$lang['NamecraneMail.resource_usage.email_archives.status'] = 'Email Archiving Status';
$lang['NamecraneMail.resource_usage.email_archives.direction'] = 'Archiving Direction';
$lang['NamecraneMail.resource_usage.email_archives.direction.in'] = 'Incoming';
$lang['NamecraneMail.resource_usage.email_archives.direction.out'] = 'Outgoing';
$lang['NamecraneMail.resource_usage.email_archives.direction.inout'] = 'Incoming + Outgoing';
$lang['NamecraneMail.resource_usage.last_updated'] = 'Last Updated';

// Buttons

$lang['NamecraneMail.button.login_portal'] = 'Login Now';

// Cron Tasks
