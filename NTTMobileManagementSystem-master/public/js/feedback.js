/**
 * Created by Dafan Wang on 2017/4/30.
 */

$(function () {
    var ajaxUrl = '/admin/content/feedback/ajax';

    $("#tb_feedback").bootstrapTable({
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
                field: "user",
                title: "User",
                sortable: true,
                formatter: function (value) {
                    if (null == value) {
                        return "Anonymous";
                    } else {
                        return value.username;
                    }
                }
            },
            {
                field: "content",
                title: "Content",
                sortable: true
            },
            {
                field: "created_at",
                title: "Post Date",
                sortable: true
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

    $(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        var id = tr.attr('data-uniqueid');

        BootstrapDialog.show({
            title: 'Delete feedback',
            message: 'Are you really want delete this feedback?',
            animate: true,
            type: BootstrapDialog.TYPE_WARNING,
            buttons: [
                {
                    label: 'Yes',
                    cssClass: 'btn-success',
                    icon: 'glyphicon glyphicon-ok-sign',
                    action: function (dialogRef) {
                        dialogRef.close();

                        ajax(ajaxUrl, {_function: 'delete', cid: id},
                            function (response) {
                                if (response.status == 'success') {
                                    // fadeout remove
                                    tr.fadeOut(500, function () {
                                        tr.remove();
                                    });
                                } else {
                                    displayError(response.data.error);
                                    $("#tb_feedback").bootstrapTable('refresh');
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
    })
});
