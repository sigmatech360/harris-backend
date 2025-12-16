<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report Attached</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #ebebeb;
            font-family: Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .email-content {
            text-align: left;
            color: #333333;
            line-height: 1.6;
        }

        .email-footer {
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .logo {
            display: block;
            margin: 0 auto 20px;
        }

        .footer-logo {
            width: 80px;
            opacity: 0.6;
            margin-bottom: 8px;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">

        <!-- Email Body -->
        <div class="email-content">
            <p>Dear User,</p>

            <p>Thank you for your request. Please find your report attached to this email.</p>

            <p>If you have any questions or need further assistance, feel free to reach out.</p>

            <p>Best regards,<br><strong>My Virtual PI</strong></p>
        </div>

        <!-- Email Footer with Image -->
        <div class="email-footer">
            <img class="logo" src="https://server.testlinkwebsitespace.com/virtual-pi-backend/public/images/logo-virtual-pi.png" alt="Virtual PI Logo" width="110" height="110">
            <br>
            <small style="color: #999;">&copy; 2025 My Virtual PI. All rights reserved.</small>
        </div>
    </div>
</body>
</html>
