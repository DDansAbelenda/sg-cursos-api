<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|alpha|max:50',
            'last_names'   => 'required|alpha|max:50',
            'address'      => 'required|string',
            'phone'        => 'required|numeric',
            'nif'          => 'required|alpha_num|max:10',
            'date_birth'   => 'required|date',
            'nationality'  => 'alpha',
            'salary'       => 'required|numeric',
            'sex'          => 'required|in:M,F',
            'is_qualified' => 'required|boolean',
        ]);

        $employee = Employee::create([
            'name'         => $request->name,
            'last_names'   => $request->last_names,
            'address'      => $request->address,
            'phone'        => $request->phone,
            'nif'          => $request->nif,
            'date_birth'   => $request->date_birth,
            'nationality'  => $request->nationality,
            'salary'       => $request->salary,
            'sex'          => $request->sex,
            'is_qualified' => $request->is_qualified,
        ]);

        return $employee;
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $employee;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'         => 'required|alpha|max:50',
            'last_names'   => 'required|alpha|max:50',
            'address'      => 'required|string',
            'phone'        => 'required|numeric',
            'nif'          => 'required|alpha_num|max:10',
            'date_birth'   => 'required|date',
            'nationality'  => 'alpha',
            'salary'       => 'required|numeric',
            'sex'          => 'required|in:M,F',
            'is_qualified' => 'required|boolean',
        ]);

        $employee->update([
            'name'         => $request->name,
            'last_names'   => $request->last_names,
            'address'      => $request->address,
            'phone'        => $request->phone,
            'nif'          => $request->nif,
            'date_birth'   => $request->date_birth,
            'nationality'  => $request->nationality,
            'salary'       => $request->salary,
            'sex'          => $request->sex,
            'is_qualified' => $request->is_qualified,
        ]);

        //Preparar mensaje
        $message = "Actualizado con éxito";
        
        return response()->json(["message"=> $message , 
                                 "employee" => $employee ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(["message"=> "Eliminado con éxito",
                                 "employee" => $employee]);
    
    }
}
