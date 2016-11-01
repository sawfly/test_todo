<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        return response(['status' => 'Ok', 'tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(['status' => 'ok']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = new Task();
        $task = $task->add($request->all());
        return empty($task->getAttributes()) ? response(['status' => 'bad request'], 400) :
            response(['status' => 'created', 'task' => $task], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if (!$task)
            return response(['status' => 'not found'], 404);
        $task->categories;
        return response(['status' => 'ok', 'task' => $task], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response(['status' => 'ok']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task)
            return response(['status' => 'not found'], 404);
        $updated = $task->updateTask($request->all());
        return $updated != [] ? response(['status' => 'updated', 'fields' => $updated, 'task' => $task], 200) :
            response(['status' => 'not updated', 'task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task)
            return response(['status' => 'not found'], 404);
        return $task->destroy($task->id) ? response(['status' => 'deleted'], 200) :
            response(['status' => 'not deleted'], 200);
    }
}
