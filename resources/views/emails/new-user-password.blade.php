<!-- resources/views/emails/new-user-password.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $company ? $company->name : 'Our System' }} - Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #ddd;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 15px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            padding: 15px;
            border-top: 1px solid #ddd;
        }
        .password {
            font-weight: bold;
            font-size: 18px;
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .company-details {
            margin-top: 30px;
            font-size: 13px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
       
        <h1>Welcome to {{ $company ? $company->name : 'Our System' }}!</h1>
    </div>

    <div class="content">
        <p>Hello {{ $user->name }},</p>

        <p>Your account has been created successfully in our system. Below are your login details:</p>

        <p><strong>Email:</strong> {{ $user->email }}</p>
        <div class="password">
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p>Please login using these credentials and change your password immediately for security reasons.</p>

        <p>If you have any questions or need assistance, please contact the system administrator.</p>

        <p>Thank you!</p>

        @if($company)
            <div class="company-details">
                <p><strong>{{ $company->name }}</strong></p>

                @if($company->website)
                    <p>Website: {{ $company->website }}</p>
                @endif

            </div>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        @if($company)
            <p>&copy; {{ date('Y') }} {{ $company->name }}. All rights reserved.</p>
        @endif
    </div>
</body>
</html>
