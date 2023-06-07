<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Notation;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index(Request $request)
    {
        $allInternships = [];
        $user = $request->user();
        if ($user->role == 2) {
            $supervisor = $user->supervisor;
            $internships = Internship::where('supervisor_id', $supervisor->id)->get();
            foreach ($internships as $internship) {
                $student = $internship->student;
                $internshipUser = $student->user;
                $allInternships[] = ["internship" => $internship, "user" => $internshipUser];
            }
            return Response(compact('allInternships'));
        }

        $student = $user->student;
        $internships = Internship::where('student_id', $student->id)->get();
        foreach ($internships as $internship) {
            $allInternships[] = ["internship" => $internship, "user" => $user];
        }
        return Response(compact('allInternships'));
    }

    public function notation(Request $request, string $id): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $internship = Internship::find($id);

//        return response($internship);
        $notation = Notation::create([
            'internship_id' => $internship->id,
            'discipline' => $request->discipline,
            'aptitude' => $request->aptitude,
            'initiative' => $request->initiative,
            'innovation' => $request->innovation,
            'acquired_knowledge' => $request->acquired_knowledge,
            'note' => $request->note,
        ]);

        return response(compact('internship', 'notation'));
    }
}
