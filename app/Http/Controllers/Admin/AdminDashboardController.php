<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreForm;
use App\Models\PostForm;
use App\Models\PdpcForm;
use App\Models\EvaluationDoc;
use App\Models\AuditObservation;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | FORM STATISTICS
        |--------------------------------------------------------------------------
        */

        $preFormCount = PreForm::count();

        $postFormCount = PostForm::count();

        $pdpcFormCount = PdpcForm::count();


        $totalForms =
            $preFormCount +
            $postFormCount +
            $pdpcFormCount;


        /*
        |--------------------------------------------------------------------------
        | ACTIVE FORMS
        |--------------------------------------------------------------------------
        */

        $activePreForms = PreForm::where(
            'status',
            'Active'
        )->count();

        $activePostForms = PostForm::where(
            'status',
            'Active'
        )->count();

        $activePdpcForms = PdpcForm::where(
            'status',
            'Active'
        )->count();


        $activeForms =
            $activePreForms +
            $activePostForms +
            $activePdpcForms;


        /*
        |--------------------------------------------------------------------------
        | DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $totalDocuments = EvaluationDoc::count();


        /*
        |--------------------------------------------------------------------------
        | OBSERVATION AUDIT
        |--------------------------------------------------------------------------
        */

        $totalAudits = AuditObservation::count();


        /*
        |--------------------------------------------------------------------------
        | RECENT OBSERVATION ACTIVITY
        |--------------------------------------------------------------------------
        */

        $recentAudits = AuditObservation::with([
            'teacher',
            'guruNew',
        ])
            ->orderByDesc('audit_date')
            ->orderByDesc('audit_time')
            ->take(5)
            ->get();


        return view(
            'admin.dashboard',
            compact(
                'totalForms',
                'activeForms',
                'totalDocuments',
                'totalAudits',
                'preFormCount',
                'postFormCount',
                'pdpcFormCount',
                'recentAudits'
            )
        );
    }
}