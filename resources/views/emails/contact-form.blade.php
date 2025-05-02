<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Form Message</h2>
        </div>
        
        <div class="content">
            <p><strong>From:</strong> {{ $email }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $message }}</p>
        </div>
        
        <div class="footer">
            <p>This email was sent from the contact form on Sant Lal's Store website.</p>
        </div>
    </div>
</body>
</html>
