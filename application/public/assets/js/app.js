var currentCategory = {id: "categories", name: ""};
var taskId;
var taskBefore = {};
$('.categories a').click(function () {
    $('.categories .active').removeClass('active');
    $(this).addClass('active');
    if ($(this)[0].name != '') {
        $('ul.tasks li').hide();
        $('.' + $(this)[0].name).show();
    } else $('ul.tasks li').show();
    var curId = $(this)[0].id;
    var curName = $(this)[0].name;
    currentCategory = {name: curName, id: curId};
});

var totalTasks = function () {
    $('.categories a span')[0].innerText = $('ul.tasks li span.text').length;
};

totalTasks();

var getCsrf = function () {
    return $('form input[type=hidden]')[0].defaultValue;
};
var changeTaskStatus = function () {
    var task = $(this).parent();
    var id = task[0].id.replace('task', '');
    var data = {};
    data._token = getCsrf();
    if (task.children('input')[0].checked === true)
        data.status_id = 2;
    else data.status_id = 0;
    $.ajax({
        method: 'PUT',
        url: "/tasks/" + id,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: data
    }).success(function (data) {
        if (data.status == 'updated') {
            if (task.hasClass('done')) {
                task.removeClass('done');
            } else task.addClass('done');
        } else {
            if (task.children('input')[0].checked === true)
                task.children('input')[0].checked = false;
            else task.children('input')[0].checked = true;
        }
    }).error(function (data) {
        if (task.children('input')[0].checked === true)
            task.children('input')[0].checked = false;
        else task.children('input')[0].checked = true;
    });
};

var bindChangeStatus = function () {
    $("input[type=checkbox]").on("change", changeTaskStatus);
};
bindChangeStatus();

$('#categoryCreate').click(function () {
    var pattern = /^[a-zA-Z0-9-_ ]{3,256}$/;
    var catName = $("input#catName");
    if (!pattern.test(catName[0].value)) {
        catName.addClass('invalid');
        return 0;
    }
    else catName.removeClass('invalid');
    var data = {name: catName[0].value};
    data._token = getCsrf();
    $.ajax({
        method: 'POST',
        url: "/categories",
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: data
    }).success(function () {
        $('<a href="#" class="list-group-item" id="category{{$category->id}}" name="{{$category->name}}">' +
            '<span class="badge">0</span>' +
            catName[0].value +
            '</a>').insertAfter($('.categories a').last());
    });
});

$('.createTask').click(function () {
    var options = '';
    var cats = $('.categories a');
    if (currentCategory.id == "categories" || currentCategory.name == "") {
        options = '<option>None</option>';
        for (var a = 1; a < cats.length; a++) {
            options += '<option value="' + cats[a].id.replace('category', '') + '">' + cats[a].name + '</option>';
        }
    } else
        options += '<option value="' + currentCategory.id.replace('category', '') + '">' + currentCategory.name + '</option>';
    $('select.selectCategory').children().detach();
    $(options).appendTo($('select.selectCategory'));
});

$('#taskCreate').click(function () {
    var pattern = /^[a-zA-Z0-9-_ ]{3,256}$/;
    var taskName = $("input#taskName");
    if (!pattern.test(taskName[0].value)) {
        taskName.addClass('invalid');
        return 0;
    }
    else taskName.removeClass('invalid');
    var dataSend = {};
    dataSend.name = taskName[0].value;
    if ($("select")[0].value != 'None')
        dataSend.category_id = $("select")[0].value;
    var category = '';
    if ($("select :selected")[0].label != 'None')
        category = $("select :selected")[0].label;
    dataSend._token = getCsrf();
    $.ajax({
        method: 'POST',
        url: "/tasks",
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: dataSend
    }).success(function (data) {
        task = '<li class="'+category+'" id="task' + data.task.id + '">' +
            '<input type="checkbox" value="">' +
            '<span class="text">' + data.task.name + '</span>' +
            '<span class="label label-success">' + category + '</span>' +
            '<div class="tools">' +
            '<i class="glyphicon glyphicon glyphicon-pencil" data-toggle="modal" data-target="#edit_task_modal"></i>' +
            '<i class="glyphicon glyphicon-remove-circle"></i>' +
            '</div>' +
            '</li>';
        $('ul.tasks').append(task);
        if (dataSend.category_id) {
            var badge = $('a#category' + dataSend.category_id + ' span');
            badge[0].innerText = parseInt(badge[0].innerText) + 1;
            totalTasks();
        }
        bindEdit();
        bindDelete();
        bindChangeStatus();
    });
});

var bindDelete = function () {
    $('li .glyphicon-remove-circle').click(function () {
        var toDelete = $(this).parent().parent();
        var id = toDelete[0].id.replace('task', '');
        var data = {};
        data._token = getCsrf();
        $.ajax({
            method: 'DELETE',
            url: "/tasks/" + id,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: data
        }).success(function (data) {
            if (data.status == 'deleted')
                toDelete.detach();
            if (toDelete[0].className != '') {
                var badge = $('div.categories .' + toDelete[0].className + ' span');
                badge[0].innerText = parseInt(badge[0].innerText) - 1;
            }
            totalTasks();
        });
    });
};

bindDelete();

var bindEdit = function () {
    $('i.glyphicon-pencil').click(function () {
        var edit = $(this).parent().parent();
        taskId = edit[0].id.replace('task', '');
        var form = $('#edit_task_modal form');
        var taskName = $('#newTaskName');
        taskName[0].placeholder = edit.children('.text')[0].innerText;
        taskBefore.name = taskName[0].placeholder;
        var options = '<option>None</option>';
        var cats = $('.categories a');
        for (var a = 1; a < cats.length; a++) {
            options += '<option value="' + cats[a].id.replace('category', '') + '">' + cats[a].name + '</option>';
        }
        $('select.allCategories').children().detach();
        $(options).appendTo($('select.allCategories'));

        for (var b = 1; b < $('select.allCategories option').length; b++) {
            if ($('select.allCategories option')[b].innerText == edit.children('span.label')[0].innerText) {
                $('select.allCategories option')[b].selected = true;
                taskBefore.category = edit.children('span.label')[0].innerText;
                taskBefore.category_id = $('select.allCategories option')[b].value;
                break;
            }
        }
        var status = $('select.status');
        if (edit.children('span').hasClass('label-success')) {
            if (edit.children('input').attr('checked') == 'checked') {
                status[0][2].selected = true;
                status_id = 2;
            } else {
                status[0][0].selected = true;
                status_id = 0;
            }
        }
        if (edit.children('span').hasClass('label-danger')) {
            if (edit.children('input').attr('checked') == 'checked') {
                status[0][2].selected = true;
                status_id = 2;
            } else {
                status[0][1].selected = true;
                status_id = 1;
            }
        }
        taskBefore.status_id = status_id;
    });
};

bindEdit();

$('#taskEdit').click(function () {
    var pattern = /^[a-zA-Z0-9-_ ]{3,256}$/;
    var editData = {};
    var taskName = $('#newTaskName');
    if (pattern.test(taskName[0].value))
        editData.name = taskName[0].value;
    editData.status_id = $('select.status :selected')[0].value;
    if($('select.allCategories :selected')[0].value != 'None')
        editData.category_id = $('select.allCategories :selected')[0].value;
    editData._token = getCsrf();
    var task = $('#task' + taskId);
    $.ajax({
        method: 'PUT',
        url: "/tasks/" + taskId,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: editData
    }).success(function (data) {
        if (data.status == 'updated') {
            if (data.task.status_id == 0) {
                task.removeClass('done');
                task.children('span.label').removeClass('label-danger').addClass('label-success');
                task.children('input')[0].checked = false;
            }
            if (data.task.status_id == 1) {
                task.removeClass('done');
                task.children('span.label').removeClass('label-success').addClass('label-danger');
                task.children('input')[0].checked = false;
            }
            if (data.task.status_id == 2) {
                task.addClass('done');
                task.children('input')[0].checked = true;
            }
            if (data.task.name != taskBefore.name) {
                task.children('span.text')[0].innerText = data.task.name;
            }
            task.children('span.label')[0].innerText = taskBefore.category;
            if (data.task.category_id != taskBefore.category_id) {
                var oldCat = $('#category' + taskBefore.category_id);
                var newCat = $('#category' + data.task.category_id);
                oldCat.children('span')[0].innerText = parseInt(oldCat.children('span')[0].innerText) - 1;
                newCat.children('span')[0].innerText = parseInt(newCat.children('span')[0].innerText) + 1;
                task.children('span.label')[0].innerText = newCat[0].name;
            }
        }
    })
});
