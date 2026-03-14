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
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-unpaid { background: #fef3c7; color: #92400e; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
        .badge-partially_paid { background: #e0e7ff; color: #3730a3; }
        .badge-cancelled { background: #f3f4f6; color: #374151; }
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
        <h1>Invoice #{{ $invoice->id }}</h1>
        <p>{{ $invoice->fee->department->university->name ?? 'Student Accounting System' }}</p>
    </div>

    <div class="content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span class="badge badge-{{ $invoice->status }}">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</span>
            <span style="font-size: 12px; color: #6b7280;">Generated: {{ now()->format('M d, Y H:i') }}</span>
        </div>

        <div class="section-title">Student Information</div>
        <table>
            <tr>
                <td class="label">Full Name</td>
                <td class="value">{{ $invoice->student->user->first_name }} {{ $invoice->student->user->last_name }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">{{ $invoice->student->user->email }}</td>
            </tr>
            <tr>
                <td class="label">Level</td>
                <td class="value">{{ $invoice->student->level }}</td>
            </tr>
            <tr>
                <td class="label">Study System</td>
                <td class="value">{{ $invoice->student->study_system }}</td>
            </tr>
            <tr>
                <td class="label">Academic Year</td>
                <td class="value">{{ $invoice->student->academic_year }}</td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td class="value">{{ $invoice->fee->department->name }}</td>
            </tr>
        </table>

        <div class="section-title">Invoice Details</div>
        <table>
            <tr>
                <td class="label">Fee Name</td>
                <td class="value">{{ $invoice->fee->name }}</td>
            </tr>
            <tr>
                <td class="label">Issued Date</td>
                <td class="value">{{ $invoice->issued_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Due Date</td>
                <td class="value">{{ $invoice->due_date->format('M d, Y') }}</td>
            </tr>
        </table>

        <div class="amount-box">
            <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Total Amount</div>
            <div class="amount">{{ number_format($invoice->fee->amount, 2) }} DZD</div>
        </div>

        <div class="footer">
            Student Accounting System &mdash; {{ now()->format('Y') }} &mdash; This document is auto-generated.
        </div>
    </div>
</body>
</html>