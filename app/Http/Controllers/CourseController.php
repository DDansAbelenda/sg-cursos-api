<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
 
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|alpha|max:50',
            'description'  => 'required|alpha_num|max:255',
            'number_hours' => 'required|integer',
            'cost'         => 'required|numeric',
        ]);

        $course = Course::create([
            'name'         => $request->name,
            'description'  => $request->description,
            'number_hours' => $request->number_hours,
            'cost'         => $request->cost,
        ]);

        return $course;
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return $course;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name'         => 'required|string|max:50',
            'description'  => 'required|string|max:255',
            'number_hours' => 'required|integer',
            'cost'         => 'required|numeric',
        ]);

        $course->update([
            'name'         => $request->name,
            'description'  => $request->description,
            'number_hours' => $request->number_hours,
            'cost'         => $request->cost,
        ]);

        //Preparar mensaje
        $message = "Actualizado con éxito";
        
        return response()->json(["message"=> $message , 
                                 "course" => $course ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(["message"=> "Eliminado con éxito",
                                 "course" => $course]);
    }
}