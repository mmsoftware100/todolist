<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PharIo\Manifest\Author;

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
            'status' => 'filled'
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

    public function sync(Request $request)
    {
        $local_data = json_decode($request->getContent(), true);
        // dd( $local_data);
        $last_sync_time = $local_data['last_sync_time'];

        //  dd($last_sync_time);

        $local_deleted_data = $local_data['deleted_data'];
        // dd($local_deleted_data);
        if (!is_null($local_deleted_data)) {
            foreach ($local_deleted_data as $value) {
                TodoList::destroy($value['sync_id']);
            }
        }

        $deleted_data = Auth::user()->todotrashed()->where('deleted_at', '>', $last_sync_time)->get();
        // return response()->json(['status' => 'success', 'result' => $deleted_data]);
        // exit;
       
       

        $local_modified_data = $local_data['modified_data'];
        // dd($local_modified_data);
        if (!is_null($local_modified_data)) {
            foreach ($local_modified_data as $value) {
                // dd($value);
                $todo = TodoList::where('id', $value['sync_id'])->first();
                // dd((string)$todo->updated_at);
                if((string)$todo->updated_at < $last_sync_time){
                    $todo->description =  $value['description'];
                    $todo->status =  $value['status'];
                    $todo->save();

                }
               
            }
        }

        $modified_data = Auth::user()->todo()->where('updated_at', '>', $last_sync_time)->get();
        // return response()->json(['status' => 'success', 'result' => $modified_data]);
        // exit;

        $local_created_data = $local_data['created_data'];
        // dd($local_created_data);
        $created_data =[];
        if (!is_null($local_created_data)) {
            foreach ($local_created_data as $value) {
                $todo = new TodoList;
                $todo->user_id =  Auth::user()->id;
                $todo->description =  $value['description'];
                $todo->status =  $value['status'];
                $todo->save();
                $todo->local_id =$value['id'];
                $created_data []= $todo;
               
            }
        }

        $server_data['deleted_data'] = $deleted_data;
        $server_data['modified_data'] = $modified_data;
        $server_data['created_data'] = $created_data;
        $server_data['last_sync_time'] =date('Y-m-d h:m:s');
        return response()->json(['status' => 'success', 'result' => $server_data]);



        
    }
}
