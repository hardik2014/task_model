<?php

namespace App\Api\V1\Controllers;
use JWTAuth;
use App\Task;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use Helpers;
    public function index()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        return $currentUser
            ->tasks()
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
    }
    public function store(Request $request)
    {
        
        $currentUser = JWTAuth::parseToken()->authenticate();
    
        $task = new Task;
    
        $task->title = $request->get('title');
        //dd($currentUser->id);
        $task->user_id=$currentUser->id;
    
        if($task->save())
            return $task;
        else
            return $this->response->error('could_not_create_task', 500);
    }
    public function show($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
    
        $task = $currentUser->tasks()->find($id);
    
        if(!$task)
            throw new NotFoundHttpException; 
    
        return $task;
    }
    
    public function update(Request $request, $id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
    
        $task = $currentUser->tasks()->find($id);
        if(!$task)
            throw new NotFoundHttpException;
    
        $task->fill($request->all());
    
        if($task->save())
            return $task;
        else
            return $this->response->error('could_not_update_task', 500);
    }
    
    public function destroy($id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
    
        $task = $currentUser->tasks()->find($id);
    
        if(!$task)
            throw new NotFoundHttpException;
    
        if($task->delete())
            return $this->response->noContent();
        else
            return $this->response->error('could_not_delete_task', 500);
    }
}
