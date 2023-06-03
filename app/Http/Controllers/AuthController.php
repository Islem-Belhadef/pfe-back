<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Your existing methods...

    public function register(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        // Validate the input
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        // Create a new user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 0,
        ]);

//         Create a new student
        $student = Student::create([
            'department' => $request->department,
            'major' => $request->major,
            'semester' => $request->semester,
            'level' => $request->level,
            'academic_year' => $request->academic_year,
            'date_of_birth' => $request->date_of_birth,
            'phone_num' => $request->phone_num,
            'student_card_num' => $request->student_card_num,
            'user_id' => $user->id
        ]);

        Auth::login($user);
        $token = $user->createToken('access_token')->plainTextToken;
        $role = 0;

        return response(compact('user', 'student', 'token', 'role'), 201);
    }

    public function login(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user();
            $token = $user->createToken('access_token')->plainTextToken;
            $role = $user->role;
            Auth::login($user);
            return response(compact('user', 'token', 'role'));
        }

        // Authentication failed
        return response(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'Successfully logged out']);
    }
}
