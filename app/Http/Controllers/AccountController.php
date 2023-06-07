<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreated;
use App\Models\HeadOfDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $users = User::all();
        return Response(compact('users'));
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
    public function store(Request $request)
    {
        $password = Str::random(12);

        $hod_user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 1,
            'email_verified_at' => date('Y-m-d h:i:sa')
        ]);

        $hod = HeadOfDepartment::create([
            'user_id' => $hod_user->id,
            'department_id' => $request->department_id,
        ]);

        Mail::to($hod_user->email)->send(new AccountCreated($hod_user->email, $password));

        return Response(compact('hod_user', 'hod'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 0) {
            $student = $user->student;
            $requests = $student->requests;
            $student->delete();
            $requests->delete();
        } else if ($user->role == 1) {
            $hod = $user->hod;
            $hod->delete();
        }
        else{
            $supervisor = $user->supervisor;
            $requests = $supervisor->requests;
            $supervisor->delete();
            $requests->delete();
        }

        $user->delete();
        return Response(['message' => 'Account and its properties deleted']);
    }
}
