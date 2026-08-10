<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\GuruNew;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GNListController extends Controller
{
    public function index(Request $request)
    {
        $principal = Auth::guard('principal')->user();

        $search = $request->search;

        $guruNew = GuruNew::where(
            'schoolID',
            $principal->schoolID
        )
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'gn_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                        ->orWhere(
                            'email',
                            'LIKE',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'phone_number',
                            'LIKE',
                            '%' . $search . '%'
                        );
                });
            })
            ->orderBy('gn_name')
            ->paginate(10)
            ->withQueryString();


        $totalTeachers = GuruNew::where(
            'schoolID',
            $principal->schoolID
        )
            ->count();


        return view(
            'principal.gn-list',
            compact(
                'guruNew',
                'totalTeachers'
            )
        );
    }
}
