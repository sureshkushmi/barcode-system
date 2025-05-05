<h1>Hi {{ $user->name }},</h1>
<p>We are sending you a reminder that your membership at Security Entrepreneurs Association will expire on {{ $user->expiry_date->toFormattedDateString() }}.</p>
<p>Please make sure to renew your membership to continue enjoying our services.</p>
<p>If you need any assistance, feel free to contact us.</p>

<p>Best regards,<br>The Security Entrepreneurs Association Team</p>
