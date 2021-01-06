<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Validator;
use App\Repositories\ValidationRepository;


class TodoController extends Controller
{
    public function __construct(ValidationRepository $vr, Request $request)
    {    
        $this->vr = $vr;
    }
    public function index()
    {
        $todos = Todo::all();
        return response()->json(
            [   'status' => 'success',
                'todos' => $todos->toArray()
            ], 200);
    }

    
    public function store(Request $request)
    {
        $fv = $this->validate($request, $this->vr->todoCreate()); 
        $todo = Todo::create($request->all());        
        return ['created' => 'true','id' => $todo->id];
    }

    
    public function show(Todo $todo)
    {
        //
    }

   
    public function edit(Todo $todo)
    {
        //
    }

    
    public function update(Request $request,$id)
    {
        $fv = $this->validate($request, $this->vr->todoUpdate());
        $todo = Todo::findOrFail($id);
        $todo->update($request->all());        
        return ['updated' => 'true','id' => $todo->id];
    }

    
    public function destroy($id)
   {
       $todo = Todo::findOrFail($id);
       $todo->delete();
       return response()
           ->json(['deleted' => true]);
   }
}
