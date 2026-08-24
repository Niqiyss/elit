<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreForm;
use App\Models\PostForm;
use App\Models\EvaluationDoc;

class ManageFormController extends Controller
{
    public function index()
    {
        // Get PRE form
        $preForm = PreForm::orderBy('formID')
            ->first();

        // Get POST / Feedback form
        $postForm = PostForm::orderBy('formID')
            ->first();

        // Get uploaded evaluation documents
        $documents = EvaluationDoc::latest('doc_id')
            ->get();

        return view(
            'admin.manage-form',
            compact(
                'preForm',
                'postForm',
                'documents'
            )
        );
    }
}