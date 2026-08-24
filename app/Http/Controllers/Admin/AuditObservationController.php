<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditObservation;
use Illuminate\Http\Request;

class AuditObservationController extends Controller
{
    // Show observation audit records
    // Show observation audit records
    public function index(Request $request)
    {
        $audits = AuditObservation::with([
            'teacher',
            'guruNew',
        ])

            // Search by observer or teacher observed
            ->when($request->search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->whereHas('teacher', function ($teacherQuery) use ($search) {
                        $teacherQuery->where(
                            'teacher_name',
                            'like',
                            '%' . $search . '%'
                        );
                    })

                        ->orWhereHas('guruNew', function ($guruQuery) use ($search) {
                            $guruQuery->where(
                                'gn_name',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            })

            // Filter role
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })

            // Filter stage
            ->when($request->stage, function ($query, $stage) {
                $query->where('stage', $stage);
            })

            // Filter date
            ->when($request->date, function ($query, $date) {
                $query->whereDate('audit_date', $date);
            })

            ->orderByDesc('audit_date')
            ->orderByDesc('audit_time')

            ->paginate(15)

            ->withQueryString();


        return view(
            'admin.audit-observation',
            compact('audits')
        );
    }
}
