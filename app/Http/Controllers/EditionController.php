<?php

namespace App\Http\Controllers;

use App\Exceptions\CourseEditionSameDate;
use App\Exceptions\CourseNotExist;
use App\Exceptions\EmployeeNotExist;
use App\Exceptions\EmployeeNotQualified;
use App\Exceptions\EmployeeTeacherNotStudent;
use App\Exceptions\StudentNotExist;
use App\Models\Course;
use App\Models\Edition;
use App\Models\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $editions = Edition::all();
        return response()->json($editions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validate_edition($request);
            $edition = Edition::create($validated);
            $employees = array();
            foreach ($request->students as $student) {
                $employees[] = $employee = Employee::find($student);
                $edition->employee_editions()->attach($employee);
            }
            return response()->json([
                'edition' => $edition,
                'employees' => $employees,
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return response()->json([
                'message' => 'El curso seleccionado ya tiene otra edición con el mismo código o fecha o ambos',
            ]);
        } catch (QueryException $exception) {
            return response()->json([
                'message' => 'El profesor o el curso seleccionados no existe',
            ]);
        } catch (EmployeeNotExist $ex) {
            return response()->json(['message' => 'El empleado seleccionado no existe']);
        } catch (EmployeeNotQualified $ex) {
            return response()->json(['message' => 'El empleado seleccionado no está calificado para dar clases']);
        } catch (CourseNotExist $ex) {
            return response()->json(['message' => 'El curso seleccionado no existe']);
        } catch (StudentNotExist $ex) {
            return response()->json(['message' => 'Hay estudiantes seleccionados que no existen']);
        } catch (EmployeeTeacherNotStudent $ex) {
            return response()->json(['message' => 'El profesor seleccionado no puede pertenecer al grupo de estudiantes']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Edition $edition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Edition $edition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Edition $edition)
    {
        //
    }

    private function validate_edition(Request $request)
    {
        //Validación general
        $validated = $request->validate([
            'code_id'        => 'required|numeric',
            'course_id'      => 'required|numeric',
            'employee_id'    => 'required|numeric',
            'place'          => 'required|string',
            'session_period' => 'required|in:F,M,A',
            'date'           => 'required|date',
        ]);

        $this->specific_validation($request);

        return $validated;
    }

    private function specific_validation(Request $request)
    {
        //Validar que el empleado seleccionado exista
        $employee = Employee::find($request->employee_id);
        if ($employee == null) {
            throw new EmployeeNotExist();
        }
        //Validar que el empleado esté calificado
        if (!$employee->is_qualified) {
            throw new EmployeeNotQualified();
        }
        //Validar que el curso seleccionado exista
        $course = Course::find($request->course_id);
        if ($course == null) {
            throw new CourseNotExist();
        }
        //Validar que los estudiantes de la edición existan
        foreach ($request->students as $student) {
            if (!Employee::all()->contains('id', $student)) {
                throw new StudentNotExist();
            }
        }
        //Validar que el empleado seleccionado como profesor no sea estudiante también
        foreach ($request->students as $student) {
            if ($student === $employee->id) {
                throw new EmployeeTeacherNotStudent();
            }
        }
    }
}
