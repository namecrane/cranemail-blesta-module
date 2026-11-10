<?php

// Basics
$lang['NamecraneMail.name'] = 'NameCrane Mail';
$lang['NamecraneMail.description'] = 'Sell CraneMail email domains from your NameCrane reseller plan.';
$lang['NamecraneMail.module_row'] = 'Server';
$lang['NamecraneMail.module_row_plural'] = 'Servers';
$lang['NamecraneMail.module_group'] = 'Server Group';

// Module management
$lang['NamecraneMail.add_module_row'] = 'Add Server';
$lang['NamecraneMail.add_module_group'] = 'Add Server Group';
$lang['NamecraneMail.manage.module_rows_title'] = 'Servers';
$lang['NamecraneMail.manage.module_rows_heading.server_name'] = 'Server Name';
$lang['NamecraneMail.manage.module_rows_heading.service_id'] = 'Reseller Plan';
$lang['NamecraneMail.manage.module_rows_heading.api_key'] = 'API Key';
$lang['NamecraneMail.manage.module_rows_heading.options'] = 'Options';
$lang['NamecraneMail.manage.service_id_any'] = 'The key\'s only plan';
$lang['NamecraneMail.manage.module_rows.edit'] = 'Edit';
$lang['NamecraneMail.manage.module_rows.delete'] = 'Delete';
$lang['NamecraneMail.manage.module_rows.confirm_delete'] = 'Are you sure you want to delete this server?';
$lang['NamecraneMail.manage.module_rows_no_results'] = 'There are no servers.';
$lang['NamecraneMail.manage.module_groups_title'] = 'Groups';
$lang['NamecraneMail.manage.module_groups_heading.name'] = 'Name';
$lang['NamecraneMail.manage.module_groups_heading.module_rows'] = 'Servers';
$lang['NamecraneMail.manage.module_groups_heading.options'] = 'Options';
$lang['NamecraneMail.manage.module_groups.edit'] = 'Edit';
$lang['NamecraneMail.manage.module_groups.delete'] = 'Delete';
$lang['NamecraneMail.manage.module_groups.confirm_delete'] = 'Are you sure you want to delete this server group?';
$lang['NamecraneMail.manage.module_groups.no_results'] = 'There are no server groups.';

// Add and edit server
$lang['NamecraneMail.add_row.box_title'] = 'NameCrane Mail - Add Server';
$lang['NamecraneMail.add_row.add_btn'] = 'Add Server';
$lang['NamecraneMail.edit_row.box_title'] = 'NameCrane Mail - Edit Server';
$lang['NamecraneMail.edit_row.edit_btn'] = 'Update Server';

// Server fields
$lang['NamecraneMail.row_meta.intro'] = 'Create an API key in the NameCrane client portal under My Profile > API Keys. Give it Full Access, or limit it to your CraneMail reseller service with read and write. The key is checked when you save.';
$lang['NamecraneMail.row_meta.server_name'] = 'Server Name';
$lang['NamecraneMail.row_meta.api_key'] = 'API Key';
$lang['NamecraneMail.row_meta.service_id'] = 'Reseller Plan Service ID (optional)';
$lang['NamecraneMail.row_meta.tooltip.server_name'] = 'A name for this server, shown only to you.';
$lang['NamecraneMail.row_meta.tooltip.api_key'] = 'Your NameCrane API key.';
$lang['NamecraneMail.row_meta.tooltip.service_id'] = 'Leave empty unless the key covers more than one CraneMail reseller plan. Then enter the service ID of the plan this server is for (the number in its portal address).';

// Tabs
$lang['NamecraneMail.tabs.overview'] = 'Email Overview';
$lang['NamecraneMail.tabs.mailboxes'] = 'Mailboxes';
$lang['NamecraneMail.tabs.aliases'] = 'Aliases';
$lang['NamecraneMail.tabs.domain_aliases'] = 'Domain Aliases';

// Package fields
$lang['NamecraneMail.package_fields.disklimit'] = 'Disk Space (GB)';
$lang['NamecraneMail.package_fields.userlimit'] = 'Max Email Users';
$lang['NamecraneMail.package_fields.useraliaslimit'] = 'Max User Aliases';
$lang['NamecraneMail.package_fields.domainaliaslimit'] = 'Max Domain Aliases';
$lang['NamecraneMail.package_fields.archive_years'] = 'Email Archiving (Years)';
$lang['NamecraneMail.package_fields.archive_direction'] = 'Email Archiving Direction';
$lang['NamecraneMail.package_fields.spamexperts'] = 'MXFilter Spam Filtering';
$lang['NamecraneMail.package_fields.spamexperts_adminaccess'] = 'MXFilter Access';
$lang['NamecraneMail.package_fields.filestorage'] = 'File Storage';
$lang['NamecraneMail.package_fields.office'] = 'Office Suite';
$lang['NamecraneMail.package_fields.webmail_sso'] = 'Webmail Login';
$lang['NamecraneMail.package_fields.mxfilter_sso'] = 'MXFilter Login';
$lang['NamecraneMail.package_fields.client_management'] = 'Client Mailbox Management';
$lang['NamecraneMail.package_fields.tooltip.userlimit'] = '0 = unlimited.';
$lang['NamecraneMail.package_fields.tooltip.useraliaslimit'] = '0 = unlimited.';
$lang['NamecraneMail.package_fields.tooltip.domainaliaslimit'] = '0 = unlimited.';
$lang['NamecraneMail.package_fields.tooltip.spamexperts_adminaccess'] = 'Which of the domain\'s administrators can open MXFilter from webmail.';
$lang['NamecraneMail.package_fields.tooltip.webmail_sso'] = 'Lets the client open any mailbox\'s webmail without its password. Turn off where privacy rules (GDPR) forbid it: NameCrane then refuses it for the domain everywhere.';
$lang['NamecraneMail.package_fields.tooltip.mxfilter_sso'] = 'Lets the client and your staff open the spam filter panel without a password. Turn off where privacy rules (GDPR) forbid it.';
$lang['NamecraneMail.package_fields.tooltip.client_management'] = 'Shows or hides the Mailboxes, Aliases and Domain Aliases tabs.';

// Options
$lang['NamecraneMail.option.disabled'] = 'Disabled';
$lang['NamecraneMail.option.enabled'] = 'Enabled';
$lang['NamecraneMail.option.allowed'] = 'Allowed';
$lang['NamecraneMail.option.turned_off'] = 'Turned off';
$lang['NamecraneMail.option.year'] = '%1$s year';
$lang['NamecraneMail.option.years'] = '%1$s years';
$lang['NamecraneMail.option.primary_admin'] = 'Primary Administrator Only';
$lang['NamecraneMail.option.all_admins'] = 'All Domain Administrators';
$lang['NamecraneMail.option.clients_manage'] = 'Clients manage mailboxes and aliases';
$lang['NamecraneMail.option.hidden_from_clients'] = 'Hidden from clients';
$lang['NamecraneMail.archive_direction.in'] = 'Incoming';
$lang['NamecraneMail.archive_direction.out'] = 'Outgoing';
$lang['NamecraneMail.archive_direction.inout'] = 'Both';
$lang['NamecraneMail.archive_direction.disabled'] = 'Off';

// Service fields
$lang['NamecraneMail.service_fields.domain'] = 'Domain';
$lang['NamecraneMail.service_fields.username'] = 'Administrator';
$lang['NamecraneMail.service_fields.password'] = 'Password';

// Statuses
$lang['NamecraneMail.status.active'] = 'Active';
$lang['NamecraneMail.status.pending'] = 'Waiting for DNS';
$lang['NamecraneMail.status.suspended'] = 'Suspended';
$lang['NamecraneMail.status.plan_suspended'] = 'Suspended (your NameCrane plan)';
$lang['NamecraneMail.status.admin_suspended'] = 'Suspended by NameCrane';
$lang['NamecraneMail.status.unknown'] = 'Unknown';

// Overview
$lang['NamecraneMail.overview.unavailable'] = 'The domain\'s details can\'t be shown right now.';
$lang['NamecraneMail.overview.verify'] = 'Add this TXT record to <strong>%1$s</strong>\'s DNS to prove it\'s yours. The mailboxes are set up a few minutes after it shows up.';
$lang['NamecraneMail.overview.open_webmail'] = 'Open Webmail';
$lang['NamecraneMail.overview.mxfilter_login'] = 'Login to MXFilter';
$lang['NamecraneMail.overview.suspended'] = 'This domain is suspended, so its mail is stopped. Contact us to have it turned back on.';
$lang['NamecraneMail.overview.usage'] = 'Usage';
$lang['NamecraneMail.overview.features'] = 'Features';
$lang['NamecraneMail.overview.sign_in'] = 'Sign-in Details';
$lang['NamecraneMail.overview.webmail_address'] = 'Webmail';
$lang['NamecraneMail.overview.dns_records'] = 'DNS Records';
$lang['NamecraneMail.overview.spf'] = 'Set these at the domain\'s DNS host. For SPF, add <code>%1$s</code> to the domain\'s SPF record, or create <code>v=spf1 %1$s ~all</code> if it has none.';
$lang['NamecraneMail.usage.storage'] = 'Storage';
$lang['NamecraneMail.usage.mailboxes'] = 'Mailboxes';
$lang['NamecraneMail.usage.aliases'] = 'Aliases';
$lang['NamecraneMail.usage.domain_aliases'] = 'Domain Aliases';
$lang['NamecraneMail.usage.of'] = 'of %1$s';
$lang['NamecraneMail.usage.used_of'] = '%1$s of %2$s';
$lang['NamecraneMail.usage.unlimited'] = 'unlimited';
$lang['NamecraneMail.feature.mxfilter'] = 'MXFilter spam filtering';
$lang['NamecraneMail.feature.file_storage'] = 'File storage';
$lang['NamecraneMail.feature.office'] = 'Office suite';
$lang['NamecraneMail.feature.archiving'] = 'Email archiving';
$lang['NamecraneMail.feature.on'] = 'On';
$lang['NamecraneMail.feature.off'] = 'Off';
$lang['NamecraneMail.dns.type'] = 'Type';
$lang['NamecraneMail.dns.name'] = 'Name';
$lang['NamecraneMail.dns.value'] = 'Value';

// Admin tab
$lang['NamecraneMail.admin.status'] = 'Status';
$lang['NamecraneMail.admin.storage_office'] = 'File storage / Office';
$lang['NamecraneMail.admin.archiving'] = '%1$s years, %2$s';
$lang['NamecraneMail.admin.sso'] = 'Password-free sign-in';
$lang['NamecraneMail.admin.sso_state'] = 'Webmail %1$s, MXFilter %2$s';
$lang['NamecraneMail.admin.verification'] = 'Verifies the domain. Its mailboxes are set up a few minutes after this record shows up.';
$lang['NamecraneMail.admin.spf'] = 'SPF with <code>%1$s</code>';

// Lists
$lang['NamecraneMail.manage_when_active'] = 'This can be managed once the domain is verified and active.';
$lang['NamecraneMail.list.count'] = '%1$s of %2$s';
$lang['NamecraneMail.edit'] = 'Edit';
$lang['NamecraneMail.delete'] = 'Delete';
$lang['NamecraneMail.cancel'] = 'Cancel';

// Mailboxes
$lang['NamecraneMail.mailboxes.add_title'] = 'Add a Mailbox';
$lang['NamecraneMail.mailboxes.edit_title'] = 'Edit %1$s';
$lang['NamecraneMail.mailboxes.address'] = 'Email address';
$lang['NamecraneMail.mailboxes.display_name'] = 'Display name';
$lang['NamecraneMail.mailboxes.password'] = 'Password';
$lang['NamecraneMail.mailboxes.new_password'] = 'New password';
$lang['NamecraneMail.mailboxes.password_help'] = 'At least 8 characters. Share it with the mailbox\'s owner.';
$lang['NamecraneMail.mailboxes.password_keep'] = 'Leave empty to keep the current password.';
$lang['NamecraneMail.mailboxes.quota'] = 'Quota (MB)';
$lang['NamecraneMail.mailboxes.quota_help'] = '0 uses the domain\'s default.';
$lang['NamecraneMail.mailboxes.admin'] = 'Domain administrator (can manage the domain from webmail)';
$lang['NamecraneMail.mailboxes.enabled'] = 'Enabled';
$lang['NamecraneMail.mailboxes.add'] = 'Add Mailbox';
$lang['NamecraneMail.mailboxes.save'] = 'Save Mailbox';
$lang['NamecraneMail.mailboxes.usage'] = 'Usage';
$lang['NamecraneMail.mailboxes.usage_of'] = '%1$s MB of %2$s MB';
$lang['NamecraneMail.mailboxes.domain_limit'] = 'Domain\'s limit';
$lang['NamecraneMail.mailboxes.primary_admin'] = 'Primary admin';
$lang['NamecraneMail.mailboxes.admin_badge'] = 'Admin';
$lang['NamecraneMail.mailboxes.disabled'] = 'Disabled';
$lang['NamecraneMail.mailboxes.webmail'] = 'Webmail';
$lang['NamecraneMail.mailboxes.confirm_delete'] = 'Delete %1$s and all of its mail? This can\'t be undone.';
$lang['NamecraneMail.mailboxes.none'] = 'No mailboxes yet.';

// Aliases
$lang['NamecraneMail.aliases.add_title'] = 'Add an Alias';
$lang['NamecraneMail.aliases.edit_title'] = 'Edit %1$s';
$lang['NamecraneMail.aliases.intro'] = 'An alias is an address with no mailbox of its own: mail sent to it goes on to the addresses you list.';
$lang['NamecraneMail.aliases.address'] = 'Alias address';
$lang['NamecraneMail.aliases.targets'] = 'Deliver to';
$lang['NamecraneMail.aliases.targets_help'] = 'One address per line.';
$lang['NamecraneMail.aliases.delivers_to'] = 'Delivers to';
$lang['NamecraneMail.aliases.catch_all'] = 'Catch-all';
$lang['NamecraneMail.aliases.add'] = 'Add Alias';
$lang['NamecraneMail.aliases.save'] = 'Save Alias';
$lang['NamecraneMail.aliases.confirm_delete'] = 'Delete the alias %1$s?';
$lang['NamecraneMail.aliases.none'] = 'No aliases yet.';
$lang['NamecraneMail.aliases.feature.catch_all'] = 'Catch-all (gets mail for addresses that don\'t exist)';
$lang['NamecraneMail.aliases.feature.show_in_gal'] = 'Show in the address book';
$lang['NamecraneMail.aliases.feature.internal_only'] = 'Only accept mail from this domain';
$lang['NamecraneMail.aliases.feature.send_from'] = 'Targets can send as this alias';
$lang['NamecraneMail.aliases.feature.all_users'] = 'Deliver to every mailbox';

// Domain aliases
$lang['NamecraneMail.domain_aliases.add_title'] = 'Add a Domain Alias';
$lang['NamecraneMail.domain_aliases.intro'] = 'A domain alias gets the same mailboxes under another domain: with <strong>example.net</strong> as an alias, mail to jane@example.net reaches jane@%1$s. Like the main domain, it needs a TXT record proving it\'s yours (you\'re shown it when you add it), and its MX records pointed here.';
$lang['NamecraneMail.domain_aliases.add'] = 'Add Domain Alias';
$lang['NamecraneMail.domain_aliases.remove'] = 'Remove';
$lang['NamecraneMail.domain_aliases.confirm_delete'] = 'Remove %1$s? Mail to it stops arriving.';
$lang['NamecraneMail.domain_aliases.none'] = 'No domain aliases yet.';

// Passwords
$lang['NamecraneMail.password.show'] = 'Show password';
$lang['NamecraneMail.password.hide'] = 'Hide password';
$lang['NamecraneMail.password.generate'] = 'Generate a password';

// Sign-in redirects
$lang['NamecraneMail.redirect.webmail'] = 'Opening webmail...';
$lang['NamecraneMail.redirect.mxfilter'] = 'Opening MXFilter...';
$lang['NamecraneMail.redirect.continue'] = 'Continue';

// Success
$lang['NamecraneMail.!success.mailbox_added'] = 'Mailbox %1$s added.';
$lang['NamecraneMail.!success.mailbox_saved'] = 'Mailbox saved.';
$lang['NamecraneMail.!success.mailbox_deleted'] = 'Mailbox deleted.';
$lang['NamecraneMail.!success.alias_added'] = 'Alias %1$s added.';
$lang['NamecraneMail.!success.alias_saved'] = 'Alias saved.';
$lang['NamecraneMail.!success.alias_deleted'] = 'Alias deleted.';
$lang['NamecraneMail.!success.domain_alias_added'] = 'Domain alias %1$s added. Point its MX records here like the main domain\'s.';
$lang['NamecraneMail.!success.domain_alias_removed'] = 'Domain alias removed.';

// Errors
$lang['NamecraneMail.!error.server_name.valid'] = 'Enter a name for the server.';
$lang['NamecraneMail.!error.api_key.valid'] = 'Enter your NameCrane API key.';
$lang['NamecraneMail.!error.api_key.refused'] = 'NameCrane didn\'t accept the API key: %1$s';
$lang['NamecraneMail.!error.api_key.no_plan'] = 'This API key can\'t reach any NameCrane Mail reseller plan. Check the key\'s access in your NameCrane account.';
$lang['NamecraneMail.!error.service_id.valid'] = 'The reseller plan\'s service ID is a number.';
$lang['NamecraneMail.!error.service_id.unreachable'] = 'This API key can\'t reach service #%1$s. It can reach: %2$s.';
$lang['NamecraneMail.!error.service_id.needed'] = 'This API key covers more than one reseller plan (%1$s). Enter the service ID of the one this server is for.';
$lang['NamecraneMail.!error.module_row.missing'] = 'An internal error occurred. The module row is unavailable.';
$lang['NamecraneMail.!error.domain.valid'] = 'Enter a valid domain, such as example.com.';
$lang['NamecraneMail.!error.domain.renamed'] = 'A domain can\'t be renamed at NameCrane. To record a different domain anyway, save without using the module.';
$lang['NamecraneMail.!error.disklimit.valid'] = 'Disk Space must be a whole number, 0 or more.';
$lang['NamecraneMail.!error.userlimit.valid'] = 'Max Email Users must be a whole number, 0 or more.';
$lang['NamecraneMail.!error.useraliaslimit.valid'] = 'Max User Aliases must be a whole number, 0 or more.';
$lang['NamecraneMail.!error.domainaliaslimit.valid'] = 'Max Domain Aliases must be a whole number, 0 or more.';
$lang['NamecraneMail.!error.archive_years.valid'] = 'Choose how many years to archive mail for.';
$lang['NamecraneMail.!error.archive_direction.valid'] = 'Archive Direction must be Incoming, Outgoing or Both.';
$lang['NamecraneMail.!error.spamexperts.valid'] = 'MXFilter Spam Filtering must be Enabled or Disabled.';
$lang['NamecraneMail.!error.spamexperts_adminaccess.valid'] = 'Choose who can open MXFilter.';
$lang['NamecraneMail.!error.filestorage.valid'] = 'File Storage must be Enabled or Disabled.';
$lang['NamecraneMail.!error.office.valid'] = 'Office Suite must be Enabled or Disabled.';
$lang['NamecraneMail.!error.switch.valid'] = 'Choose Allowed or Turned off.';
$lang['NamecraneMail.!error.webmail_sso.off'] = 'Signing in to webmail without a password is turned off for this service.';
$lang['NamecraneMail.!error.mxfilter_sso.off'] = 'Signing in to MXFilter without a password is turned off for this service.';
$lang['NamecraneMail.!error.sso.url'] = 'NameCrane didn\'t send a sign-in address. Try again shortly.';
