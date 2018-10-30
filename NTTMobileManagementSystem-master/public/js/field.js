/**
 * Created by Dafan Wang on 2017/4/29.
 */
$(function () {
    var ajaxUrl = '/admin/settings/field/ajax';

    $("#tb_field").bootstrapTable({
        method: 'post',
        url: ajaxUrl,
        search: false,
        pagination: false,
        showRefresh: false,
        showToggle: false,
        showColumns: false,
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
                sortable: false,
                order: 'asc'
            },
            {
                field: "field_name",
                title: "Field Name",
                sortable: false
            },
            {
                field: "define",
                title: "Definition",
                sortable: false,
                editable: {
                    type: "select",
                    title: "Definition",
                    disabled: false,
                    mode: "popup",
                    send: 'always',
                    source: [
                        {value: null, text: 'Empty'},
                        {value: 'StartDate', text: 'Start Date'},
                        {value: 'EndDate', text: 'End Date'},
                        {value: 'Venue', text: 'Venue'},
                        {value: 'Category', text: 'Category'},
                        {value: 'Poster', text: 'Poster'},
                        {value: 'Description', text: 'Description'}
                    ]
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

    $(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        var eventID = tr.attr('data-uniqueid');

        BootstrapDialog.show({
            title: 'Delete Field',
            message: 'Are you really want delete this field?<br> <strong style="color: red;">Note: this action will delete all related data.</strong>',
            animate: true,
            type: BootstrapDialog.TYPE_WARNING,
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
                                        $("#tb_field").bootstrapTable('refresh');
                                    });
                                } else {
                                    displayError(response.data.error);
                                    $("#tb_field").bootstrapTable('refresh');
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
});

