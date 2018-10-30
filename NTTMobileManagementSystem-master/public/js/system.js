/**
 * Created by Dafan Wang on 2017/4/30.
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    var path_array = window.location.pathname.split('/');
    var schemeless_url = '//' + window.location.host + window.location.pathname;
    if (path_array[2] == 'dashboard') {
        schemeless_url = window.location.protocol + '//' + window.location.host + '/admin/' + path_array[2];
    } else {
        schemeless_url = window.location.protocol + '//' + window.location.host + '/admin/' + path_array[2] + '/' + path_array[3];
    }

    $('ul.treeview-menu>li').find('a[href="' + schemeless_url + '"]').closest('li').addClass('active');
    $('ul.treeview-menu>li').find('a[href="' + schemeless_url + '"]').closest('li.treeview').addClass('active');
    $('.sidebar-menu>li').find('a[href="' + schemeless_url + '"]').closest('li').addClass('active');
});

function ajax(url, data, $success, $fail, $always) {
    $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: 'json',
            success: $success,
            error: function (response) {
                displayError('Unknown error.');
                typeof $fail === "function" && $fail(response);
            }
        }
    ).always($always);
}

function obj2Html(Obj) {
    var html = '<ol>';

    // if is object
    if ((typeof Obj === "object") && (Obj !== null)) {
        for (var k in Obj) {
            if (Obj.hasOwnProperty(k)) {
                html += ('<li>' + k + ': ' + Obj[k] + '</li>');
            }
        }
        html += ('</ol>');

        return html;
    } else {
        // if not, return the original data
        return Obj;
    }
}

function displayError(message) {
    if ((typeof message === "object") && (message !== null)) {
        BootstrapDialog.show({
            title: 'Something went wrong',
            message: "Errors: " + obj2Html(message),
            type: BootstrapDialog.TYPE_DANGER
        })
    } else {
        // display error
        BootstrapDialog.show({
            title: 'Something went wrong',
            message: "Error: " + message,
            type: BootstrapDialog.TYPE_DANGER
        });
    }
}

function displaySuccess(message) {
    BootstrapDialog.show({
        title: 'Success',
        message: obj2Html(message),
        type: BootstrapDialog.TYPE_SUCCESS
    });
}

function cloneTable($table) {
    // clone the object
    var cloned = $table.clone();

    // replace id
    cloned.find('[id]').each(function () {
        //Perform the same replace as above
        var $th = $(this);
        var newID = $th.attr('id').replace(/\d+$/, function (str) {
            return (parseInt(str)) + 1;
        });

        $th.attr('id', newID);
    });

    // replace for
    cloned.find('[for]').each(function () {
        //Perform the same replace as above
        var $th = $(this);
        var newID = $th.attr('for').replace(/\d+$/, function (str) {
            return (parseInt(str)) + 1;
        });

        $th.attr('for', newID);
    });

    return cloned;
}

