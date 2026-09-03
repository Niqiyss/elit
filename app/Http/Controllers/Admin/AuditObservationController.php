<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditObservationController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values.
        $search = trim((string) $request->input('search', ''));
        $role = $request->input('role', '');
        $stage = $request->input('stage', '');
        $date = $request->input('date', '');

        // Build observation audit query.
        $auditQuery = DB::table('audit_observation')
            ->leftJoin(
                'teacher',
                'audit_observation.teacherID',
                '=',
                'teacher.teacherID'
            )
            ->leftJoin(
                'guru_new',
                'audit_observation.gn_id',
                '=',
                'guru_new.gn_id'
            )
            ->select(
                'audit_observation.*',
                'teacher.teacher_name',
                'guru_new.gn_name'
            );

        // Apply observer or teacher search.
        if ($search !== '') {

            $auditQuery->where(function ($query) use ($search) {

                $query
                    ->where(
                        'teacher.teacher_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'guru_new.gn_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'guru_new.gn_id',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        // Apply role filter.
        if ($role !== '') {

            $auditQuery->where(
                'audit_observation.role',
                $role
            );
        }

        // Apply stage filter.
        if ($stage !== '') {

            $auditQuery->where(
                'audit_observation.stage',
                $stage
            );
        }

        // Apply date filter.
        if ($date !== '') {

            $auditQuery->whereDate(
                'audit_observation.audit_date',
                $date
            );
        }

        // Get observation audit records.
        $audits = $auditQuery
            ->orderByDesc(
                'audit_observation.audit_date'
            )
            ->orderByDesc(
                'audit_observation.audit_time'
            )
            ->paginate(15)
            ->withQueryString();

        // Return observation audit page.
        return view(
            'admin.audit-observation',
            compact(
                'audits',
                'search',
                'role',
                'stage',
                'date'
            )
        );
    }
}
