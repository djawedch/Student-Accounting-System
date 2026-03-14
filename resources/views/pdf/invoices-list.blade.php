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
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-unpaid { background: #fef3c7; color: #92400e; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
        .badge-partially_paid { background: #e0e7ff; color: #3730a3; }
        .badge-cancelled { background: #f3f4f6; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background-color: #4f46e5; color: #fff; }
        thead th { padding: 10px 8px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        tbody td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        .footer { margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 10px; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoices Report</h1>
        <p>Generated: {{ now()->format('M d, Y H:i') }} &mdash; Total: {{ $invoices->count() }} invoices</p>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Fee</th>
                    <th>Department</th>
                    <th>Amount (DZD)</th>
                    <th>Status</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->student->user->first_name }} {{ $invoice->student->user->last_name }}</td>
                    <td>{{ $invoice->fee->name }}</td>
                    <td>{{ $invoice->fee->department->name }}</td>
                    <td>{{ number_format($invoice->fee->amount, 2) }}</td>
                    <td><span class="badge badge-{{ $invoice->status }}">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span></td>
                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:#6b7280;">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Student Accounting System &mdash; {{ now()->format('Y') }} &mdash; This document is auto-generated.
        </div>
    </div>
</body>
</html>