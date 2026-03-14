<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
        .header { background-color: #4f46e5; color: #fff; padding: 24px 32px; }
        .header h1 { font-size: 22px; font-weight: bold; }
        .header p { font-size: 12px; margin-top: 4px; opacity: 0.85; }
        .content { padding: 32px; }
        .section-title { font-size: 14px; font-weight: bold; color: #4f46e5; border-bottom: 2px solid #4f46e5; padding-bottom: 6px; margin-bottom: 16px; margin-top: 24px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px 4px; vertical-align: top; }
        table td.label { width: 40%; color: #6b7280; font-size: 12px; }
        table td.value { font-weight: 500; }
        .amount-box { background: #eef2ff; border-left: 4px solid #4f46e5; padding: 16px 20px; margin-top: 24px; border-radius: 4px; }
        .amount-box .amount { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .footer { margin-top: 48px; border-top: 1px solid #e5e7eb; padding-top: 12px; font-size: 11px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Receipt #{{ $payment->id }}</h1>
        <p>{{ $payment->invoice->fee->department->university->name ?? 'Student Accounting System' }}</p>
    </div>

    <div class="content">
        <div style="text-align: right; font-size: 12px; color: #6b7280; margin-bottom: 8px;">
            Generated: {{ now()->format('M d, Y H:i') }}
        </div>

        <div class="section-title">Student Information</div>
        <table>
            <tr>
                <td class="label">Full Name</td>
                <td class="value">{{ $payment->invoice->student->user->first_name }} {{ $payment->invoice->student->user->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">{{ $payment->invoice->student->user->email }}</td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td class="value">{{ $payment->invoice->fee->department->name }}</td>
            </tr>
        </table>

        <div class="section-title">Payment Details</div>
        <table>
            <tr>
                <td class="label">Invoice ID</td>
                <td class="value">#{{ $payment->invoice_id }}</td>
            </tr>
            <tr>
                <td class="label">Fee Name</td>
                <td class="value">{{ $payment->invoice->fee->name }}</td>
            </tr>
            <tr>
                <td class="label">Payment Method</td>
                <td class="value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Date</td>
                <td class="value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
            </tr>
            @if($payment->reference)
            <tr>
                <td class="label">Reference</td>
                <td class="value">{{ $payment->reference }}</td>
            </tr>
            @endif
        </table>

        <div class="amount-box">
            <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Amount Paid</div>
            <div class="amount">{{ number_format($payment->amount, 2) }} DZD</div>
        </div>

        <div class="footer">
            Student Accounting System &mdash; {{ now()->format('Y') }} &mdash; This document is auto-generated.
        </div>
    </div>
</body>
</html>