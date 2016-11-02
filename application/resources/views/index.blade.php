<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>To-Do List</title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">

    <!-- NAVBAR START -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">TO-DO List</a>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->

    <!-- CONTENT START -->
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="panel panel-default categories">
                <div class="panel-heading lead clearfix">
                    Categories
                    <button type="button" class="btn btn-success pull-right" data-toggle="modal"
                            data-target="#create_category_modal">
                        Create New Category
                    </button>
                </div>
                <div class="panel-body list-group">
                    <a href="#" class="list-group-item active category" id="categories">
                        <span class="badge">0</span>
                        All
                    </a>
                    @foreach ($categories as $category)
                        <a href="#" class="list-group-item {{$category->name}}" id="category{{$category->id}}"
                           name="{{$category->name}}">
                            <span class="badge">
                                @if($category->tasks != '')
                                    {{$category->tasks}}
                                @else
                                    0
                                @endif
                            </span>
                            {{$category->name}}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading lead clearfix">
                    Tasks
                    <button type="button" class="btn btn-success pull-right createTask" data-toggle="modal"
                            data-target="#create_task_modal">
                        Create New Task
                    </button>
                </div>
                <div class="panel-body">
                    <ul class="todo-list ui-sortable tasks">
                        @foreach ($tasks as $task)
                            @if($task->status_id == 2)
                                <li class="done {{$task->category}}" id="task{{$task->id}}">
                                    <input type="checkbox" checked="checked" value="">
                                    <span class="text">{{$task->name}}</span>
                                    <span class="label label-success">{{$task->category}}</span>
                                    <div class="tools">
                                        <i class="glyphicon glyphicon glyphicon-pencil" data-toggle="modal"
                                           data-target="#edit_task_modal"></i>
                                        <i class="glyphicon glyphicon-remove-circle"></i>
                                    </div>
                                </li>
                            @elseif($task->status_id == 1)
                                <li class="{{$task->category}}" id="task{{$task->id}}">
                                    <input type="checkbox" value="">
                                    <span class="text">{{$task->name}}</span>
                                    <span class="label label-danger">{{$task->category}}</span>
                                    <div class="tools">
                                        <i class="glyphicon glyphicon glyphicon-pencil" data-toggle="modal"
                                           data-target="#edit_task_modal"></i>
                                        <i class="glyphicon glyphicon-remove-circle"></i>
                                    </div>
                                </li>
                            @else
                                <li class="{{$task->category}}" id="task{{$task->id}}">
                                    <input type="checkbox" value="">
                                    <span class="text">{{$task->name}}</span>
                                    <span class="label label-success">{{$task->category}}</span>
                                    <div class="tools">
                                        <i class="glyphicon glyphicon glyphicon-pencil" data-toggle="modal"
                                           data-target="#edit_task_modal"></i>
                                        <i class="glyphicon glyphicon-remove-circle"></i>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT END -->

</div>

<!-- CATEGORY MODAL START -->
<div id="create_category_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create New Category</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>List Name</label>
                        <input type="text" class="form-control" placeholder="List Name" name="catName" id="catName">
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="categoryCreate">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- CATEGORY MODAL END -->

<!-- TASK MODAL START -->
<div id="create_task_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create New Task</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Task</label>
                        <input type="text" class="form-control" placeholder="Task" id="taskName">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control selectCategory">
                        </select>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="taskCreate">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- TASK MODAL END -->

<!-- TASK EDIT START -->
<div id="edit_task_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Task</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Task" id="newTaskName">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control allCategories">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control status">
                            <option value=0>Opened</option>
                            <option value=1>Active</option>
                            <option value=2>Closed</option>
                        </select>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="taskEdit">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- TASK EDIT END -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
