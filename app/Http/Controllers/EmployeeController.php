<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\UniqueConstraintViolationException;
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
        $validated=$request->validate([
            'name'         =>'required|string|max:50',
            'last_names'   =>'required|string|max:50',
            'address'      =>'required|string',
            'phone'        =>'required|numeric',
            'nif'          =>'required|alpha_num|size:10',
            'date_birth'   =>'required|date',
            'nationality'  =>'required|alpha',
            'salary'       =>'required|numeric',
            'sex'          =>'required|in:M,F',
            'is_qualified' =>'required|boolean',
        ]);     
        
        
        try{
            $employee = Employee::create($validated);
            return $employee;
        }
        catch(UniqueConstraintViolationException $exception){
            return response()->json([
                'message'=> 'Ya existe un empleado con ese NIF',
            ]);
        }
        
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
        $validated=$request->validate([
            'name'         =>'required|string|max:50',
            'last_names'   =>'required|string|max:50',
            'address'      =>'required|string',
            'phone'        =>'required|numeric',
            'nif'          =>'required|alpha_num|size:10',
            'date_birth'   =>'required|date',
            'nationality'  =>'required|alpha',
            'salary'       =>'required|numeric',
            'sex'          =>'required|in:M,F',
            'is_qualified' =>'required|boolean',
        ]);

        $employee->update($validated);

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
