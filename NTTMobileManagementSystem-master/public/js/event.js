/**
 * Created by Dafan Wang on 2017/4/29.
 */
$(function () {
    var ajaxUrl = '/admin/content/event/ajax';

    $("#tb_event").bootstrapTable({
        method: 'post',
        url: ajaxUrl,
        search: true,
        pagination: true,
        showRefresh: true,
        showToggle: true,
        showColumns: true,
        sortOrder: 'desc',
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
                field: "title",
                title: "Title",
                sortable: true,
                editable: {
                    type: "text",
                    title: "Title",
                    disabled: false,
                    mode: "popup",
                    send: 'always',
                    validate: function (value) {
                        if (!$.trim(value)) {
                            return 'Can not be empty';
                        }
                    }
                }
            },
            {
                field: "author",
                title: "Author",
                sortable: true
            },
            {
                field: "comments_count",
                title: "Comments",
                sortable: true
            },
            {
                field: "view",
                title: "Views",
                sortable: true
            },
            {
                field: "featured",
                title: "Featured",
                sortable: true,
                editable: {
                    type: "select",
                    title: "Sticky",
                    disabled: false,
                    mode: "popup",
                    send: 'always',
                    source: [
                        {value: 0, text: 'No'},
                        {value: 1, text: 'Yes'}
                    ]
                }
            },
            {
                field: "sticky",
                title: "Sticky",
                sortable: true,
                editable: {
                    type: "select",
                    title: "Sticky",
                    disabled: false,
                    mode: "popup",
                    send: 'always',
                    source: [
                        {value: 0, text: 'No'},
                        {value: 1, text: 'Yes'}
                    ]
                }
            },
            {
                field: "priority",
                title: "Priority",
                editable: {
                    type: "text",
                    title: "Priority",
                    disabled: false,
                    mode: "popup",
                    send: 'always',
                    validate: function (value) {
                        if (!$.trim(value) && !isInt(value)) {
                            return 'Must be an integer';
                        }
                    }
                }
            },
            {
                field: "created_at",
                title: "Publish Date",
                sortable: true
            },
            {
                field: "updated_at",
                title: "Update Date",
                sortable: true
            },
            {
                field: "published",
                title: "Status",
                formatter: function (value, row, index) {
                    if (value == 0) {
                        return '<span class="label label-warning">Pending</span>';
                    } else {
                        return '<span class="label label-success">Published</span>';
                    }
                }
            },
            {
                title: "Edit",
                formatter: function (value, row, index) {
                    return '<a href="/admin/content/event/' + row.id + '"><button class="btn btn-warning btn-xs" data-title="Edit"><span class="glyphicon glyphicon-pencil"></span></button></a>';
                }
            },
            {
                title: "Toggle",
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
            row['_function'] = 'edit';

            $.ajax({
                url: ajaxUrl,
                dataType: 'JSON',
                method: 'post',
                data: row,
                success: function (response, newValue) {
                    if (response.status == 'error' || response.status == 'fail') {
                        displayError(response.data.error);
                    } else {
                        displaySuccess("successfully modified the event");
                    }
                },
                error: function () {
                    displayError('submit failed');
                }
            });
        }

    });

    $(document).on('click', '.action-toggle', function (e) {
        var eventID = $(this).closest('tr').attr('data-uniqueid');
        var label = $(this).closest('tr').find('span').first();
        var span = $(this).find('span');

        // add animation
        span.addClass('glyphicon-refresh-animate');

        ajax(ajaxUrl,
            {
                _function: 'toggle',
                id: eventID
            },
            function (response) {
                if (response.status == 'success') {
                    if (label.hasClass('label-warning')) {
                        label.removeClass('label-warning').addClass('label-success').html('Published');
                    } else {
                        label.removeClass('label-success').addClass('label-warning').html('Pending');
                    }
                } else {
                    displayError(response.data.error);
                    $("#tb_event").bootstrapTable('refresh');
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
        var eventID = tr.attr('data-uniqueid');

        BootstrapDialog.show({
            title: 'Delete event',
            message: 'Are you really want delete this event?',
            animate: true,
            type: BootstrapDialog.TYPE_DANGER,
            buttons: [
                {
                    label: 'Yes',
                    cssClass: 'btn-success',
                    icon: 'glyphicon glyphicon-ok-sign',
                    action: function (dialogRef) {
                        dialogRef.close();

                        ajax(ajaxUrl, {_function: 'delete', id: eventID},
                            function (response) {
                                if (response.status == 'success') {
                                    // fadeout remove
                                    tr.fadeOut(500, function () {
                                        tr.remove();
                                        $("#tb_event").bootstrapTable('refresh');
                                    });
                                } else {
                                    displayError(response.data.error);
                                    $("#tb_event").bootstrapTable('refresh');
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

    function isInt(value) {
        return !isNaN(value) &&
            parseInt(Number(value)) == value &&
            !isNaN(parseInt(value, 10));
    }
});

