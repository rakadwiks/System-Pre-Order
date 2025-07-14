<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order's Notification</title>
    <style>
        body {
            margin: 0;
            background-color: #f4f4f7;
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            color: #202124;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: #34a853;
            /* Green for completed */
            color: white;
            padding: 24px;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
        }

        .email-body {
            padding: 32px 24px;
        }

        .email-body h2 {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .status-completed {
            text-align: center;
            margin-bottom: 24px;
        }

        .status-completed h1 {
            font-size: 32px;
            color: #34a853;
            margin: 0;
        }

        .status-completed span {
            font-size: 48px;
            display: block;
            margin-bottom: 8px;
        }

        .info-box {
            background-color: #f1f3f4;
            border-left: 4px solid #34a853;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
        }

        .info-box p {
            margin: 4px 0;
        }

        .button {
            display: inline-block;
            margin-top: 24px;
            padding: 10px 20px;
            background-color: #34a853;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }

        .email-footer {
            background-color: #f9f9f9;
            padding: 24px;
            font-size: 13px;
            color: #666;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .email-footer p {
            margin: 4px 0;
        }

        @media only screen and (max-width: 620px) {
            .email-body {
                padding: 24px 16px;
            }

            .email-header {
                font-size: 18px;
                padding: 16px;
            }

            .email-footer {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-body">
            <div class="status-completed">
                <span>✅</span>
                <h1>Installation Completed</h1>
            </div>

            <h2><strong>Your Order Has Been Approved</strong></h2>
            <div class="info-box">
                <p><strong>Order Code:</strong> {{ $order->code_po }}</p>
                <p><strong>Date Installed:</strong> {{ $order->installed_date->format('d M Y')}}</p>
                <p><strong>Technician:</strong> {{ Auth::user()->name }}</p>
            </div>

            <p>If you have questions or need clarification, please contact our support team.</p>

            <a href="mailto:itteam@daehan.co.id" style="color: white" class="button">Contact Support</a>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} PT Daehan Global. All rights reserved.<br />
            This is an automated message. Please do not reply directly.
        </div>
    </div>
</body>

</html>