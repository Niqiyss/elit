<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationDoc;

class ManageFormController extends Controller
{
    public function index()
    {
        // Get all uploaded evaluation documents
        $documents = EvaluationDoc::orderBy(
            'uploaded_at',
            'desc'
        )->get();

        return view(
            'admin.manage-form',
            compact('documents')
        );
    }
}