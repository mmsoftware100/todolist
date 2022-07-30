<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $todo = Auth::user()->todo()->get();
        return response()->json(['status' => 'success', 'result' => $todo]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          
            'description' => 'required',
            'status' => 'required'
        ]);
        if (Auth::user()->todo()->Create($request->all())) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'fail']);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = TodoList::where('id', $id)->get();
        return response()->json($todo);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $todo = TodoList::where('id', $id)->get();
        return view('todo.edittodo', ['todos' => $todo]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'todo' => 'filled',
            'description' => 'filled',
            'category' => 'filled'
        ]);
        $todo = TodoList::find($id);
        if ($todo->fill($request->all())->save()) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (TodoList::destroy($id)) {
            return response()->json(['status' => 'success']);
        }
    }
}
