<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Validation\Rule;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success'=>true,
            'data'=>Student::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email',
            'course'     => 'required|string|max:255',
            'age'        => 'required|integer|min:1',
        ]);
        $student=Student::create($validated);
        
        return response()->json([
            'success'=>true,
            'message'=>'Student created successfully',
            'data'=>$student,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        //
    }
}
