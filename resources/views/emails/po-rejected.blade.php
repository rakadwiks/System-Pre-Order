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
            background-color:
                {{ $color ?? '#1a73e8' }}
            ;
            color: white;
            padding: 24px;
            font-size: 20px;
            font-weight: 600;
        }

        .email-body {
            padding: 32px 24px;
        }

        .email-body h2 {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .info-box {
            background-color: #f1f3f4;
            border-left: 4px solid
                {{ $color ?? '#1a73e8' }}
            ;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
        }

        .info-box p {
            margin: 4px 0;
        }

        .reason-box {
            background-color: #fefefe;
            border-left: 4px solid
                {{ $color ?? '#1a73e8' }}
            ;
            padding: 16px;
            border-radius: 4px;
            margin-top: 12px;
        }

        .button {
            display: inline-block;
            margin-top: 24px;
            padding: 10px 20px;
            background-color:
                {{ $color ?? '#1a73e8' }}
            ;
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
        <div class="email-header">
            Order Notification
        </div>
        <div class="email-body">
            <h2><strong>Your Order Has Been Rejected</strong></h2>

            <div class="info-box">
                <p><strong>PO Code:</strong> {{ $order->code_po }}</p>
                <p><strong>Rejected By:</strong> {{ Auth::user()->name }}</p>
            </div>

            @isset($reason)
                <p><strong>Reason:</strong></p>
                <div class="reason-box">
                    <p>{{ $reason }}</p>
                </div>
            @endisset

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