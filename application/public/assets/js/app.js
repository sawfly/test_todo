var currentCategory = {id: "categories", name: ""};
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
var cats = $('.categories a span');
function countCats(cats) {
    var total = 0;
    for (var a = 1; a < cats.length; a++) {
        var t = cats[a].innerText;
        if (t != '')
            total += parseInt(t);
    }
    return total;
}
cats[0].innerText = countCats(cats);

var changeTaskStatus = function () {
    var task = $(this).parent();
    var id = task[0].id;
    var data = {};
    if (task.children('input')[0].checked === true)
        data.status_id = 1;
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

$("input[type=checkbox]").on("change", changeTaskStatus);

$('#categoryCreate').click(function () {
    var pattern = /^[a-zA-Z0-9-_ ]{3,256}$/;
    var catName = $("input#catName");
    if (!pattern.test(catName[0].value)) {
        catName.addClass('invalid');
        return 0;
    }
    else catName.removeClass('invalid');
    var data = {name: catName[0].value};
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
    $.ajax({
        method: 'POST',
        url: "/tasks",
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        data: dataSend
    }).success(function (data) {
        task = '<li class="{{$task->category}}" id="task"' + data.task.id + '>' +
            '<input type="checkbox" value="">' +
            '<span class="text">' + data.task.name + '</span>' +
            '<span class="label label-success">' + category + '</span>' +
            '<div class="tools">' +
            '<i class="glyphicon glyphicon glyphicon-pencil"></i>' +
            '<i class="glyphicon glyphicon-remove-circle"></i>' +
            '</div>' +
            '</li>';
        $(task).insertAfter($('ul.tasks li').last());
        if (dataSend.category_id) {
            var badge = $('a#category'+dataSend.category_id+' span');
            badge[0].innerText = parseInt(badge[0].innerText)+1;
            var badges = $('a#categories span');
            badges[0].innerText = parseInt(badges[0].innerText)+1;
        }
    });
});

$('li .glyphicon-remove-circle').click(function () {
    var toDelete = $(this).parent().parent();
    var id = toDelete[0].id.replace('task', '');
    console.log();

    $.ajax({
        method: 'DELETE',
        url: "/tasks/" + id,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8'//,
        // data: data
    }).success(function (data) {
        if (data.status == 'deleted')
            toDelete.detach();
    });
});