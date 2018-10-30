/**
 * Created by Dafan Wang on 2017/4/30.
 */

$(function () {
    var ajaxUrl = '/admin/role/user/ajax';

    $("#tb_user").bootstrapTable({
        method: 'post',
        url: ajaxUrl,
        search: true,
        pagination: true,
        showRefresh: true,
        showToggle: true,
        showColumns: true,
        sortOrder: 'asc',
        locale: 'en-US',
        sidePagination: 'server',
        iconSize: 'sm',
        toolbar: '#TableEventsToolbar',
        pageSize: 15,
        contentType: 'application/x-www-form-urlencoded',
        idField: 'id',
        uniqueId: 'id',
        columns: [
            {
                field: "id",
                title: "ID",
                sortable: true,
                order: 'desc'
            },
            {
                field: "username",
                title: "User Name",
                sortable: true,
                formatter: function (value, row, index) {
                    return '<a href="./user/profile/' + row.id + '">' + value + '</a>';
                }
            },
            {
                field: "email",
                title: "E-main",
                sortable: true
            },
            {
                field: "gender",
                title: "Gender",
                sortable: true,
                formatter: function (value, row, index) {
                    if (value == 0) {
                        return 'Unknown';
                    } else if (value == 1) {
                        return 'Male';
                    } else if (value == 2) {
                        return 'Female';
                    } else {
                        return 'Error';
                    }
                }
            },
            {
                field: "register_ip",
                title: "Register IP",
                sortable: true
            },
            {
                field: "login_ip",
                title: "Last Login IP",
                sortable: true
            },
            {
                field: "updated_at",
                title: "Last Login Date",
                sortable: true
            },
            {
                field: "created_at",
                title: "Register Date",
                sortable: true
            },
            {
                field: "banned",
                title: "Status",
                formatter: function (value, row, index) {
                    if (value == 1) {
                        return '<span class="label label-danger">Banned</span>';
                    } else {
                        return '<span class="label label-success">Normal</span>';
                    }
                }
            },
            {
                title: "Ban",
                width: '20px',
                formatter: function (value, row, index) {
                    return '<button class="btn btn-info btn-xs action-toggle" id="' + row.id + '" data-title="Toggle"><span class="glyphicon glyphicon-refresh"></span></button>';
                }
            },
            {
                title: "Delete",
                width: '20px',
                formatter: function (value, row, index) {
                    return '<button class="btn btn-danger btn-xs confirm-delete" data-title="Delete"><span class="glyphicon glyphicon-trash"></span></button>';
                }
            }
        ],

        responseHandler: function (res) {
            return {
                rows: res.data.data,
                total: res.data.total
            }
        },
        queryParams: function (params) {
            params._function = 'getTable';

            return params;
        },
        icons: {
            refresh: 'glyphicon-repeat',
            toggle: 'glyphicon-list-alt',
            columns: 'glyphicon-list'
        },
        onEditableSave: function (field, row, oldValue, $el) {
            row['_function'] = 'editTitle';

            $.ajax({
                url: ajaxUrl,
                dataType: 'JSON',
                method: 'post',
                _function: 'editTitle',
                data: row,
                success: function (response, newValue) {
                    if (response.status == 'error' || response.status == 'fail')
                        alert(response.data.error);
                    else
                        alert("successfully modified title");
                },
                error: function () {
                    alert("submit failed");
                }
            });
        }

    });

    $(document).on('click', '.action-toggle', function (e) {
        var uid = $(this).closest('tr').attr('data-uniqueid');
        var label = $(this).closest('tr').find('span').first();
        var span = $(this).find('span');

        // add animation
        span.addClass('glyphicon-refresh-animate');

        ajax(ajaxUrl,
            {
                _function: 'toggle',
                uid: uid
            },
            function (response) {
                if (response.status == 'success') {
                    if (label.hasClass('label-danger')) {
                        label.removeClass('label-danger').addClass('label-success').html('Normal');
                    } else {
                        label.removeClass('label-success').addClass('label-danger').html('Banned');
                    }
                } else {
                    displayError(response.data.error);
                }
            },
            null,
            function () {
                // remove animation
                span.removeClass('glyphicon-refresh-animate');
            }
        );
    });

    $(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        var uid = tr.attr('data-uniqueid');

        BootstrapDialog.show({
            title: 'Delete user',
            message: 'Are you really want delete this user?',
            animate: true,
            type: BootstrapDialog.TYPE_WARNING,
            buttons: [
                {
                    label: 'Yes',
                    cssClass: 'btn-success',
                    icon: 'glyphicon glyphicon-ok-sign',
                    action: function (dialogRef) {
                        dialogRef.close();

                        ajax(ajaxUrl, {_function: 'delete', uid: uid},
                            function (response) {
                                if (response.status == 'success') {
                                    // fadeout remove
                                    tr.fadeOut(500, function () {
                                        tr.remove();
                                    });
                                } else {
                                    displayError(response.data.error);
                                    $("#tb_user").bootstrapTable('refresh');
                                }
                            }
                        );
                    }
                },
                {
                    label: 'No',
                    icon: 'glyphicon glyphicon-remove',
                    action: function (dialogRef) {
                        dialogRef.close();
                    }
                }
            ]
        });
    });

    $('#btnYes').on('click', function (e) {
        var target = $('tr.remove-flag');

        ajax(ajaxUrl,
            {
                _function: 'delete',
                uid: target.attr('data-uniqueid')
            },

            function (response) {
                if (response.status == 'success') {
                    // fadeout remove
                    target.fadeOut(500, function () {
                        target.remove();
                    });
                } else {
                    displayError(response.data.error);
                }
            }
        );

        $('#delete').modal('hide');
    });

});
