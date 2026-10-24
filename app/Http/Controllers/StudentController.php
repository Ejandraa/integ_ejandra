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
            'student_id' => 'required|integer|unique:students,student_id',
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
    public function show(Student $student)
    {
        return response()->json([
            'success'=>true,
            'data'=>$student,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated=$request->validate([
        'student_id' => [
            'required',
            'integer',
            Rule::unique('students', 'student_id')->ignore($student->id),
        ],
        'name' => 'required|string|max:255',
        'email'=>[
            'required',
            'email',
            Rule::unique('students','email')->ignore($student->id)],
        'course'=>'required|string|max:255',
        'age'=>'required|integer|min:1',

        ]);
        $student->update($validated);
        return response()->json([
            'success'=>true,
            'data'=>$student,
            'message'=>'success student update!',

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json([
            'success'=>true,
            'message'=>'student successfully delete',
            
        ]);
    }
}