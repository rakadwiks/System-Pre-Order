<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmation</title>
    <style>
        body {
            margin: 0;
            background-color: #f4f4f7;
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            color: #202124;
        }

        .email-wrapper {
            max-width: 640px;
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
            font-size: 22px;
            font-weight: bold;
            text-align: center;
        }

        .email-body {
            padding: 32px 24px;
        }

        .order-summary {
            margin-top: 24px;
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        .order-summary th,
        .order-summary td {
            padding: 12px;
            text-align: left;
        }

        .order-summary th {
            background-color: #f1f3f4;
        }

        .order-summary tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .info-box {
            background-color: #f1f3f4;
            border-left: 4px solid
                {{ $color ?? '#1a73e8' }}
            ;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
            font-size: 14px;
        }

        .info-box p {
            margin: 4px 0;
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
                font-size: 20px;
            }

            .order-summary th,
            .order-summary td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-header">
            🛒 Order Confirmation
        </div>
        <div class="email-body">
            <p>Hello <strong>{{ $order->ticket?->user->name ?? 'Customer' }}</strong>,</p>
            <p>Your order has been successfully approved. Below are the details:</p>

            <div class="info-box">
                <p><strong>Order Code:</strong> {{ $order->code_po }}</p>
                <p><strong>Ticket Code:</strong> {{ $order->ticket?->code_ticket ?? '-' }}</p>
                <p><strong>Approved By:</strong> {{ Auth::user()->name }}</p>
            </div>

            <h3>Order Items</h3>
            <table class="order-summary">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $order->product?->name_product }}</td>
                        <td>{{ $order->total }}</td>
                    </tr>
                </tbody>
            </table>

            <p>If you have questions or need clarification, please contact our support team.</p>

            <a href="mailto:itteam@daehan.co.id" style="color:white;" class="button">Contact Support</a>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} PT Daehan Global. All rights reserved.<br />
            This is an automated message. Please do not reply directly.
        </div>
    </div>
</body>

</html>