<?php
namespace App\Http\Controllers\Admin;

use App\Filters\{InvoiceFilter, PaymentFilter};
use App\Http\Controllers\Controller;
use App\Models\{Invoice, Payment};
use App\Scopes\{InvoiceRoleScope, PaymentRoleScope};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function invoicePdf(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load('student.user', 'fee.department.university');

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->id . '.pdf');
    }

    public function paymentPdf(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load('invoice.student.user', 'invoice.fee.department.university');

        $pdf = Pdf::loadView('pdf.payment', compact('payment'));

        return $pdf->download('payment-' . $payment->id . '.pdf');
    }

    public function paymentsListPdf(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $user = Auth::user();
        $query = Payment::with('invoice.student.user', 'invoice.fee');

        $payments = (new PaymentFilter($request))
            ->apply((new PaymentRoleScope)->apply($query, $user))
            ->orderBy('payment_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.payments-list', compact('payments'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('payments-' . now()->format('Y-m-d') . '.pdf');
    }

    public function invoicesListPdf(Request $request)
    {
        $this->authorize('viewAny', Invoice::class);

        $user = Auth::user();
        $query = Invoice::with('student.user', 'fee.department');

        $invoices = (new InvoiceFilter($request))
            ->apply((new InvoiceRoleScope)->apply($query, $user))
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.invoices-list', compact('invoices'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('invoices-' . now()->format('Y-m-d') . '.pdf');
    }
}