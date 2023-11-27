<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\EmployeeException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::orderBy('id')->get();
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validate_employee($request);
            $employee = Employee::create($validated);
            return response()->json([
                "employee" => $employee,
                "message" => "Empleado creado con éxito"
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return response()->json([
                'errors' => 'Ya existe un empleado con ese NIF',
            ], 422);
        } catch (EmployeeException $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $employee;
    }

    public function employee_get_all(Employee $employee)
    {   //Obteniendo los cursos

        $courses_teach = $this->get_courses_teach($employee);
        $courses_study  = $this->get_courses_study($employee);
        return response()->json([
            'study_in'   => $courses_study,
            'teach_in'   => $courses_teach,
        ]);
    }



    //Obtener todos los cursos donde el empleado da clases
    private function get_courses_teach(Employee $employee)
    {   // Utiliza pluck para obtener los IDs de todas los cursos del profesor
        $courseIds = $employee->editions()->pluck('course_id')->unique();

        // Obtén los modelos de cursos correspondientes a los IDs únicos
        $courses = Course::whereIn('id', $courseIds)->get();

        return $courses;
    }

    //Obtener todos los cursos donde el empleado recibe clases
    private function get_courses_study(Employee $employee)
    {
        // Utiliza pluck para obtener los IDs de todas los cursos del profesor
        $courseIds = $employee->edition_study()->pluck('course_id')->unique();

        // Obtén los modelos de cursos correspondientes a los IDs únicos
        $courses = Course::whereIn('id', $courseIds)->get();

        return $courses;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {
            $employee->update($this->validate_employee($request));
            //Preparar mensaje
            $message = "Actualizado con éxito";
            return response()->json([
                "message" => $message,
                "employee" => $employee
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            return response()->json([
                'errors' => 'Ya existe un empleado con ese NIF',
            ], 422);
        } catch (EmployeeException $exception) {
            return response()->json(['errors' => $exception->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return response()->json([
                "message" => "Eliminado con éxito",
            ]);
        } catch (QueryException $exception) {
            return response()->json([
                'errors' => 'No es posible eliminar porque el empleado es professor de una o varias ediciones.',
            ], 422);
        }
    }

    public function isProfessor(Employee $employee)
    {
        return response()->json([
            'isProfessor' => $employee->editions()->count() > 0
        ]);
    }
    private function validate_employee(Request $request)
    {
        $rules = [
            'name'         => 'required|string|max:50',
            'last_names'   => 'required|string|max:50',
            'address'      => 'required|string',
            'phone'        => 'required|string|regex:/^[1-9][0-9]*$/|size:8',
            'nif'          => 'required|alpha_num|size:10',
            'date_birth'   => 'required|date',
            'nationality'  => 'required|alpha',
            'salary'       => 'required|numeric|min:1',
            'sex'          => 'required|in:Masculino,Femenino',
            'is_qualified' => 'required|boolean',
        ];

        $messages = [
            'name.required'        => 'El nombre es un campo requerido',
            'name.string'          => 'El nombre debe ser una cadena de texto',
            'name.max'             => 'El nombre debe tener un máximo de 50 caracteres',
            'last_names.required'  => 'Los apellidos es un campo requerido',
            'last_names.string'    => 'Los apellidos deben ser una cadena de texto',
            'last_names.max'       => 'Los apellidos deben tener un máximo de 50 caracteres',
            'address.required'     => 'La dirección es un campo requerido',
            'address.string'       => 'La dirección debe ser una cadena de texto',
            'phone.required'       => 'El teléfono es un campo requerido',
            'phone.string'         => 'El teléfono no debe exceder los 255 caracteres',
            'phone.size'           => 'El teléfono debe tener 8 números',
            'phone.regex'          => 'El teléfono debe contener solo números',
            'nif.required'         => 'El NIF es un campo requerido',
            'nif.alpha_num'        => 'El NIF es una cadena alfanumérica sin espacios en blanco',
            'nif.size'             => 'El NIF es debe contener exáctamente 10 caractéres',
            'date_birth.required'  => 'La fecha de nacimiento es un campo requerido',
            'date_birth.date'      => 'La fecha debe ser un formato de fecha válido',
            'nationality.required' => 'La nacionalidad es un campo requerido',
            'nationality.alpha'    => 'La nacionalidad debe contener letras solamente y sin espacios',
            'salary.required'      => 'El salario es un campo requerido',
            'salary.numeric'       => 'El salario debe contener solamente números',
            'salary.min'       => 'El salario debe ser positivo',
            'sex.required'         => 'El sexo es un campo requerido',
            'sex.in'               => 'El sexo solo puede tomar los valores: Masculino(M) y Femenino(F)',
            'is_qualified.required' => 'Es campo ¿Es calificado? es requerido',
            'is_qualified.boolean' => 'El campo ¿Es calificado? es de tipo boolean',

        ];
        //Validar que la fecha de nacimiento del empleado tenga un mínimo de 18 años antes de la actual
        $dateBirth = Carbon::parse($request->date_birth);
        $minAge = Carbon::now()->subYears(18);

        if ($dateBirth->greaterThanOrEqualTo($minAge)) {
            throw new EmployeeException('La fecha de nacimiento debe ser al menos 18 años antes de la fecha actual.');
        }

        //Se crea el validador pasándole la entrada de la request, las reglas y los mensajes
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new EmployeeException($validator->errors()->first());
        }
        return $request->all();
    }

    //Obtener los trabajadores que sean calificados
    public function get_qualified_employee()
    {
        return Employee::where('is_qualified', true)->get();
    }
}
