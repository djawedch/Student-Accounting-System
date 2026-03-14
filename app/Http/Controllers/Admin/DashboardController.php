<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Invoice, Payment, Student, University, Department, StudentScholarship};
use Illuminate\Support\Facades\{Auth, DB};

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $studentQuery     = Student::query();
        $invoiceQuery     = Invoice::query();
        $paymentQuery     = Payment::query();
        $awardQuery       = StudentScholarship::query();

        match ($user->role) {
            'university_admin' => (function () use (&$studentQuery, &$invoiceQuery, &$paymentQuery, &$awardQuery, $user) {
                $studentQuery->whereHas('user', fn($q) => $q->where('university_id', $user->university_id));
                $invoiceQuery->whereHas('student.user', fn($q) => $q->where('university_id', $user->university_id));
                $paymentQuery->whereHas('invoice.student.user', fn($q) => $q->where('university_id', $user->university_id));
                $awardQuery->whereHas('student.user', fn($q) => $q->where('university_id', $user->university_id));
            })(),
            'department_admin', 'staff_admin' => (function () use (&$studentQuery, &$invoiceQuery, &$paymentQuery, &$awardQuery, $user) {
                $studentQuery->whereHas('user', fn($q) => $q->where('department_id', $user->department_id));
                $invoiceQuery->whereHas('student.user', fn($q) => $q->where('department_id', $user->department_id));
                $paymentQuery->whereHas('invoice.student.user', fn($q) => $q->where('department_id', $user->department_id));
                $awardQuery->whereHas('student.user', fn($q) => $q->where('department_id', $user->department_id));
            })(),
            default => null,
        };

        $totalStudents          = (clone $studentQuery)->count();
        $totalInvoices          = (clone $invoiceQuery)->count();
        $totalCollected         = (clone $paymentQuery)->sum('amount');
        $totalUnpaid            = (clone $invoiceQuery)->where('status', 'unpaid')->count();
        $totalOverdue           = (clone $invoiceQuery)->where('status', 'overdue')->count();
        $totalPaid              = (clone $invoiceQuery)->where('status', 'paid')->count();
        $totalPartiallyPaid     = (clone $invoiceQuery)->where('status', 'partially_paid')->count();
        $totalScholarshipAwards = (clone $awardQuery)->count();

        // super_admin only
        $totalUniversities = $user->role === 'super_admin' ? University::count() : null;
        $totalDepartments  = $user->role === 'super_admin' ? Department::count() : null;

        // Payments per month — last 6 months
        $paymentsPerMonth = (clone $paymentQuery)
            ->select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'label' => \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->format('M Y'),
                'total' => round($row->total, 2),
            ]);

        return view('admin.dashboard', compact(
            'user',
            'totalStudents',
            'totalInvoices',
            'totalCollected',
            'totalUnpaid',
            'totalOverdue',
            'totalPaid',
            'totalPartiallyPaid',
            'totalScholarshipAwards',
            'totalUniversities',
            'totalDepartments',
            'paymentsPerMonth'
        ));
    }
}