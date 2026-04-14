<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>License Expiry Notice</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f8fafc; padding:24px; color:#0f172a;">
    <div style="max-width:640px; margin:0 auto; background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:24px;">
        <h2 style="margin-top:0; color:#0f172a;">
            {{ $type === 30 ? 'Urgent License Expiry Notice' : 'License Expiry Notice' }}
        </h2>

        <p>Good day,</p>

        <p>
            Please be informed that the security guard license of
            <strong>{{ $guard->full_name }}</strong> is set to expire on
            <strong>{{ $expirationDate }}</strong>.
        </p>

        <p>
            Attached to this email is the official memorandum for your reference and necessary action.
        </p>

        <p>
            Date generated: <strong>{{ $date }}</strong>
        </p>

        <p style="margin-bottom:0;">
            Regards,<br>
            Security Guard Management System
        </p>
    </div>
</body>
</html>