<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Edition;
use App\Models\Employee;
use App\Exceptions\EditionExceptions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EditionController extends Controller
{
    public function index()
    {
        $editions_json = [];
        $editions = Edition::orderBy('id')->get();
        foreach ($editions as $edition) {
            $edition_json = $this->json_edition($edition);
            $editions_json[] = $edition_json;
        }
        return response()->json($editions_json);
    }

    private function json_edition($edition)
    {
        return array(
            'id'             => $edition->id,
            'code_id'        => $edition->code_id,
            'course'         => $edition->course,
            'employee'       => $edition->employee,
            'place'          => $edition->place,
            'session_period' => $edition->session_period,
            'date'           => Carbon::createFromFormat('Y-m-d', $edition->date)->format('d/m/Y'),
            'students'       => $edition->employee_editions,
        );
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $this->validate_edition($request);
            
            $edition = Edition::create($validated);

            //El método attach pone nuevas tuplas en la tabla sin afectar  eliminar relaciones anteriores
            $edition->employee_editions()->attach($request->students);

            //Se vuelve a cargar el objeto edition para tomar el formato de fecha de la base datos
            //Y que no existan problemas al momento de crear el json en json_edition
            $edition = Edition::find($edition->id);

            //Se envia el json
            return response()->json([
                'message' => 'Curso creado con éxito',
                'edition' => $this->json_edition($edition),
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return response()->json([
                'errors' => 'El curso seleccionado ya tiene otra edición con el mismo código o fecha o ambos',
            ], 422);
        } catch (QueryException $exception) {
            return response()->json([
                'errors' => 'El profesor o el curso seleccionados no existe',
            ], 422);
        } catch (EditionExceptions $ex) {
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    public function show(Edition $edition)
    {
        return $edition;
    }

    public function update(Request $request, Edition $edition)
    {
        try {
            $edition->update($this->validate_edition($request));
            $edition->employee_editions()->sync($request->students);
            //Se vuelve a cargar el objeto edition para tomar el formato de fecha de la base datos
            //Y que no existan problemas al momento de crear el json en json_edition
            $edition = Edition::find($edition->id);
            return response()->json([
                'message' => 'Actualizado con éxito',
                'edition' => $this->json_edition($edition),
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return response()->json([
                'errors' => 'El curso seleccionado ya tiene otra edición con el mismo código o fecha o ambos',
            ], 422);
        } catch (QueryException $exception) {
            return response()->json([
                'errors' => 'El profesor o el curso seleccionados no existe',
            ], 422);
        } catch (EditionExceptions $ex) {
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    public function destroy(Edition $edition)
    {
        $edition->delete();
        return response()->json([
            "message" => "Eliminado con éxito",
        ]);
    }

    private function validate_edition(Request $request)
    {
        //Validación general
        //Se crean las reglas del validador
        $rules = ([
            'code_id'        => 'required|numeric',
            'course_id'      => 'required|numeric',
            'employee_id'    => 'required|numeric',
            'place'          => 'required|string',
            'session_period' => 'required|in:Tiempo Completo,Mañana,Tarde',
            'date'           => 'required|date',
        ]);
        //Se crean los mensajes de respuesta ante cualquier problema de validación
        $messages = [
            'code_id.required'        => 'El código de la edición es requerido',
            'code_id.numeric'         => 'El código de la edición solo puede tener números',
            'course_id.required'      => 'El identificador de la edición es requerido',
            'course_id.numeric'       => 'El identificador de la edición solo puede tener números',
            'employee_id.required'    => 'El identificador del empleado es requerido',
            'employee_id.numeric'     => 'El identificador del empleado solo puede tener números',
            'place.required'          => 'El lugar es un campo requerido',
            'place.string'            => 'El lugar debe ser una cadena de texto',
            'session_period.required' => 'La sesión es un campo requerido',
            'session_period.in'       => 'La sesión solo puede tomar los valores de tiempo completo, mañana o tarde',
            'date.required'           => 'La fecha es un campo requerido',
            'date.date'               => 'La fecha debe ser un formato de fecha válido',
        ];
        //Se crea el validador pasándole la entrada de la request, las reglas y los mensajes
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new EditionExceptions($validator->errors()->first());
        }
        $this->specific_validation($request);

        $validated = $request->validate($rules);
        return $validated;
    }

    private function specific_validation(Request $request)
    {
        //Validar que el empleado seleccionado exista
        $employee = Employee::find($request->employee_id);
        if ($employee == null) {
            throw new EditionExceptions('El empleado seleccionado no existe');
        }
        //Validar que el empleado esté calificado
        if (!$employee->is_qualified) {
            throw new EditionExceptions('El empleado seleccionado no está calificado para dar clases');
        }
        //Validar que el curso seleccionado exista
        $course = Course::find($request->course_id);
        if ($course == null) {
            throw new EditionExceptions('El curso seleccionado no existe');
        }
        //Validar que los estudiantes de la edición existan
        foreach ($request->students as $student) {
            if (!Employee::all()->contains('id', $student)) {
                throw new EditionExceptions('Hay estudiantes seleccionados que no existen');
            }
        }
        //Validar que el empleado seleccionado como profesor no sea estudiante también
        foreach ($request->students as $student) {
            if ($student === $employee->id) {
                throw new EditionExceptions('El profesor seleccionado no puede pertenecer al grupo de estudiantes');
            }
        }
    }

    //Obtener los trabajadores que sean calificados
    public function get_qualified_employee()
    {
        return Employee::where('is_qualified', true)->get();
    }
}
