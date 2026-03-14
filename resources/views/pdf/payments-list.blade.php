<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        .header { background-color: #4f46e5; color: #fff; padding: 20px 28px; margin-bottom: 24px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 11px; margin-top: 4px; opacity: 0.85; }
        .content { padding: 0 28px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background-color: #4f46e5; color: #fff; }
        thead th { padding: 10px 8px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        tbody td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        .total-row { background-color: #eef2ff; font-weight: bold; }
        .footer { margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 10px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payments Report</h1>
        <p>Generated: {{ now()->format('M d, Y H:i') }} &mdash; Total: {{ $payments->count() }} payments</p>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Fee</th>
                    <th>Method</th>
                    <th>Amount (DZD)</th>
                    <th>Reference</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->invoice->student->user->first_name }} {{ $payment->invoice->student->user->last_name }}</td>
                    <td>{{ $payment->invoice->fee->name }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->reference ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:#6b7280;">No payments found.</td></tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="4" style="text-align:right;">Total Collected:</td>
                    <td>{{ number_format($payments->sum('amount'), 2) }} DZD</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            Student Accounting System &mdash; {{ now()->format('Y') }} &mdash; This document is auto-generated.
        </div>
    </div>
</body>
</html>