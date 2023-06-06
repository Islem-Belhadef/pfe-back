<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index(Request $request) {
        $user  = $request->user();
        if ($user->role == 2) {
            $internships = Internship::where('supervisor_id', $user->id)->get();
            return Response(compact('internships'));
        }
        $student = $user->student;
        $internships = Internship::where('student_id', $student->id)->get();
        return Response(compact('internships'));
    }
}
