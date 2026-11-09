<?php

// The NameCrane API host. Leave it as namecrane.com.
Configure::set('NamecraneMail.api_host', 'namecrane.com');

// The welcome email a new package starts with.
Configure::set('NamecraneMail.email_templates', [
    'en_us' => [
        'lang' => 'en_us',
        'text' => 'Thanks for choosing us for your email!

Your email domain {service.namecrane_mail_domain} is set up. Manage its mailboxes, aliases and DNS records from our client area: open the service from your Dashboard.

Webmail: https://workspace.org/
Administrator: {service.namecrane_mail_username}
Password: {service.namecrane_mail_password}

Thank you for your business!',
        'html' => '<p>Thanks for choosing us for your email!</p>
<p>Your email domain <strong>{service.namecrane_mail_domain}</strong> is set up. Manage its mailboxes, aliases and DNS records from our client area: open the service from your Dashboard.</p>
<p>Webmail: <a href="https://workspace.org/">https://workspace.org/</a><br />Administrator: {service.namecrane_mail_username}<br />Password: {service.namecrane_mail_password}</p>
<p>Thank you for your business!</p>',
    ],
]);
