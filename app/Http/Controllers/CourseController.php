<?php

namespace App\Http\Controllers;


use App\Exceptions\CourseExceptions;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::orderBy('id')->get();
        return response()->json($courses);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validate_course($request);
            $course = Course::create($validated);
            return response()->json([
                "course" => $course,
                "message" => "Curso creado con éxito"
            ]);
        } catch (CourseExceptions $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }
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
        try {
            $course->update($this->validate_course($request));
            return response()->json([
                "message" => "Actualizado con éxito",
                "course" => $course
            ]);
        } catch (CourseExceptions $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json([
            "message" => "Eliminado con éxito",
        ]);
    }

    private function validate_course(Request $request)
    {
        $rules = [
            'name'         => 'required|string|max:50',
            'number_hours' => 'required|integer|min:1',
            'description'  => 'required|string',
            'cost'         => 'required|numeric|min:0',
        ];

        $messages = [
            'name.required'         => 'El nombre es un campo requerido',
            'name.string'           => 'El nombre debe ser una cadena de caracteres',
            'name.max'              => 'El nombre debe tener como máximo 50 caracteres',
            'description.required'  => 'La descripción es un campo requerido',
            'description.string'    => 'La descripción debe ser una cadena de caracteres',
            'number_hours.required' => 'El número de horas es un campo requerido',
            'number_hours.integer'  => 'El número de horas debe ser entero',
            'number_hours.min'      => 'El número de horas debe ser positivo',
            'cost.required'         => 'El costo es un campo requerido',
            'cost.numeric'          => 'El costo debe ser un valor numérico',
            'cost.min'              => 'El costo es un número no negativo',          
        ];
        //Se crea el validador pasándole la entrada de la request, las reglas y los mensajes
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new CourseExceptions($validator->errors()->first());
        }
        return $request->all();
    }
}
