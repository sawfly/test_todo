<?php

namespace App\Http\Controllers;

use App\Category;
use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Controller
{
    public function indexAction()
    {
        $categories = Category::withTasks();
        $tasks = Task::allWithCategoryName();
        return view('index', ['categories' => $categories, 'tasks' => $tasks]);
    }
}
