<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
   public function __construct()
   {
       $this->middleware('auth');
   }
    public function index(Request $request , Task $task)
    {
        $allTasks = $task->whereIn('user_id' , $request->user())->with('user');
        $tasks = $allTasks->orderBy('created_at' , 'desc')->take(20)->get();

        return response()->json([
            'tasks' => $tasks
        ]);
    }

  
    public function create()
    {
    
    }

   
    public function store(Request $request)
    {
        //Validation
        $this->validate($request,[
            'name'=>'required|max:255'
        ]);
        //Create Task
       $task= $request->user()->tasks()->create([
            'name' =>$request->name,
        ]);
        //Return Response
        return response()->json($task->with('user')->find($task->id));
    }

    
    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return response()->json([
            'task' => $task
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $task = Task::findOrFail($id);
        $task->update($input);

        return response()->json($task->with('user')->find($task->id));
    }

    
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
    }
}
