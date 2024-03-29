<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreated;
use App\Models\Internship;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        if ($request->user()->role == 1) {
            $internshipRequests = [];
            $requests = \App\Models\Request::where('status', 0)->get();
            foreach ($requests as $internshipRequest) {
                $student = $internshipRequest->student;
                $user = $student->user;
                $internshipRequests[] = ["request" => $internshipRequest, "user" => $user];
            }
            return response(compact('internshipRequests'));
        } else if ($request->user()->role == 2) {
            $internshipRequests = [];
            $supervisor_id = $request->user()->supervisor->id;
            $requests = \App\Models\Request::where('supervisor_id', $supervisor_id)->where('status', 1)->get();
            foreach ($requests as $internshipRequest) {
                $student = $internshipRequest->student;
                $user = $student->user;
                $internshipRequests[] = ["request" => $internshipRequest, "user" => $user];
            }
            return response(compact('internshipRequests'));
        }

        $internshipRequests = [];
        $user = $request->user();
        $student_id = $user->student->id;
        $requests = \App\Models\Request::where('student_id', $student_id)->get();
        foreach ($requests as $internshipRequest) {
            $internshipRequests[] = ["request" => $internshipRequest, "user" => $user];
        }
        return response(compact('internshipRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $student_id = $user->student->id;

        if (!User::where('email', $request->supervisor_email)->exists()) {

            $internshipRequest = \App\Models\Request::create([
                'student_id' => $student_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => (round((strtotime($request->end_date) - strtotime($request->start_date)) / 86400)),
                'supervisor_id' => null,
                'supervisor_email' => $request->supervisor_email,
                'supervisor_first_name' => $request->supervisor_first_name,
                'supervisor_last_name' => $request->supervisor_last_name,
                'status' => 0,
                'rejection_motive' => null,
                'title' => $request->title
            ]);

            return response()->json(
                [
                    "message" => "internship request successful",
                    "request" => $internshipRequest,
                ],
                201
            );
        }

        $supervisorUser = User::where('email', $request->supervisor_email)->first();
        $supervisor = $supervisorUser->supervisor;

        $demand = \App\Models\Request::create([
            'student_id' => $student_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => (round((strtotime($request->end_date) - strtotime($request->start_date)) / 86400)),
            'supervisor_id' => $supervisor->id,
            'supervisor_email' => $request->supervisor_email,
            'supervisor_first_name' => $request->supervisor_first_name,
            'supervisor_last_name' => $request->supervisor_last_name,
            'company' => $request->company,
            'status' => 0,
            'rejection_motive' => null,
            'title' => $request->title,
            'motivational_letter' => $request->motivational_letter
        ]);

        return response()->json(
            [
                "message" => "supervisor account exists demand created successfully",
                "demand" => $demand, "supervisorUser" => $supervisorUser, "supervisor" => $supervisor
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
    {
        $internshipRequest = \App\Models\Request::findOrFail($id);

        // status:2,4 => rejected
        if ($request->status == 2 or $request->status == 4) {
            $internshipRequest->rejection_motive = $request->rejection_motive;
            $internshipRequest->status = $request->status;
            $internshipRequest->save();
            $message = "Request refused successfully";
            return response(compact('internshipRequest', 'message'),);
        }

//        status:1 => accepted by HOD
        if ($request->status == 1) {

            $password = Str::random(12);

            $user = User::create([
                'email' => $internshipRequest->supervisor_email,
                'password' => bcrypt($password),
                'first_name' => $internshipRequest->supervisor_first_name,
                'last_name' => $internshipRequest->supervisor_last_name,
                'role' => 2
            ]);

            $supervisor = Supervisor::create([
                'user_id' => $user->id,
            ]);

            $internshipRequest->status = $request->status;
            $internshipRequest->supervisor_id = $supervisor->id;
            $internshipRequest->save();

            Mail::to($internshipRequest->supervisor_email)->send(new AccountCreated($internshipRequest->supervisor_email, $password));

            return Response(['message' => 'internship accepted by hod and supervisor account created successfully', 'supervisorUser' => $user, 'supervisor' => $supervisor]);
        }

        // status:3 => accepted by HOD and supervisor
        if ($request->status == 3) {
            $internship = Internship::create([
                'student_id' => $internshipRequest->student_id,
                'supervisor_id' => $internshipRequest->supervisor_id,
                'start_date' => $internshipRequest->start_date,
                'end_date' => $internshipRequest->end_date,
                'duration' => $internshipRequest->duration,
            ]);

            $internshipRequest->status = $request->status;
            $internshipRequest->save();

            $message = "Request accepted successfully";

            return response(compact('message', 'internshipRequest', 'internship'));
        }

        return response(compact('internshipRequest'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $internshipRequest = \App\Models\Request::findOrFail($id);
        $internshipRequest->delete();
    }
}
