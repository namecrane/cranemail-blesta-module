<?php
Configure::set('NamecraneMail.email_templates', [
   'en_us' => [
       'lang' => 'en_us',
       'text' => 'Thanks for choosing us for your mails services!

Your server is now active and you can manage it through our client area by clicking the "Manage" button next to the server on your Dashboard.

You may also use the following login details:

Mail Login URL: <a href="https://workspace.org/">https://workspace.org/</a>
User: {service.username}
Pass: {service.password}

Thank you for your business!',
       'html' => '<p>Thanks for choosing us for your Mail service!</p>
<p>Your service is now active and you can manage it through our client area by clicking the "Manage" button next to the server on your Dashboard.</p>
<p>Here are more details regarding your server:</p>
<p>Login Page: <a href="https://workspace.org/">https://workspace.org/</a></p>
User: {service.username}<br />Pass: {service.password}</p>
<p>Thank you for your business!</p>'
   ]
]);


