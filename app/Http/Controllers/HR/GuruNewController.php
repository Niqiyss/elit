<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\GuruNew;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruNewController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values
        $search = trim((string) $request->search);
        $schoolID = $request->schoolID;
        $status = $request->status;
        $appointedDate = $request->appointed_date;

        // Get new teachers
        $guruNews = GuruNew::with('school')

            // Search teacher
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('gn_name', 'like', '%' . $search . '%')
                        ->orWhere('gn_id', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('ic_number', 'like', '%' . $search . '%');
                });
            })

            // Filter school
            ->when($schoolID, function ($query, $schoolID) {
                $query->where('schoolID', $schoolID);
            })

            // Filter status
            ->when($status, function ($query, $status) {
                $query->where('current_status', $status);
            })

            // Filter appointed date
            ->when($appointedDate, function ($query, $appointedDate) {
                $query->whereDate('appointed_date', $appointedDate);
            })

            ->orderBy('gn_name')
            ->paginate(10)
            ->withQueryString();

        // Get schools for filter and manage modal
        $schools = School::orderBy('school_name')->get();

        return view(
            'hr.gurunew.index',
            compact(
                'guruNews',
                'schools'
            )
        );
    }


    public function create()
    {
        $schools = School::where('vacancy', '>', 0)
            ->orderBy('school_name')
            ->get();

        return view('hr.gurunew.create', compact('schools'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'ic_number' => [
                'required',
                'digits:12',
                'unique:guru_new,ic_number',
            ],

            'gn_name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone_number' => [
                'required',
                'string',
                'max:20',
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:guru_new,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[^A-Za-z0-9]/',
                'confirmed',
            ],

            'appointed_date' => [
                'required',
                'date',
            ],

            'schoolID' => [
                'required',
                'exists:school,schoolID',
            ],
        ]);


        $ic = $request->ic_number;

        $lastDigit = (int) substr($ic, -1);

        $gender = $lastDigit % 2 === 0
            ? 'Female'
            : 'Male';


        DB::statement(
            'CALL sp_register_guru_new(?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->ic_number,
                $request->gn_name,
                $request->phone_number,
                $request->email,
                $gender,
                Hash::make($request->password),
                $request->appointed_date,
                Auth::guard('hr')->id(),
                $request->schoolID,
            ]
        );


        return redirect()
            ->route('hr.gurunew.index')
            ->with('success', 'New Teacher registered successfully.');
    }

    public function update(Request $request, $gn_id)
    {
        $request->validate([
            'schoolID' => [
                'required',
                'exists:school,schoolID',
            ],

            'appointed_date' => [
                'required',
                'date',
            ],

            'current_status' => [
                'required',
                'in:Inactive,Active,Complete',
            ],
        ]);


        $guru = GuruNew::findOrFail($gn_id);

        $guru->schoolID = $request->schoolID;
        $guru->appointed_date = $request->appointed_date;
        $guru->current_status = $request->current_status;

        $guru->save();


        return redirect()
            ->route('hr.gurunew.index')
            ->with('success', 'New Teacher details updated successfully');
    }
}
