/**
 * Created by Dafan Wang on 2017/4/30.
 */

$(function () {
    var ajaxUrl = '/admin/content/comment/ajax';

    $("#tb_comment").bootstrapTable({
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
        toolbar: '#toolbar',
        pageSize: 15,
        contentType: 'application/x-www-form-urlencoded',
        idField: 'id',
        uniqueId: 'id',
        clickToSelect: true,
        columns: [
            {
                field: 'state',
                checkbox: true
            },
            {
                field: "event",
                title: "Event",
                sortable: true,
                formatter: function (value, row, element) {
                    return value.title;
                }
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
                field: "display",
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
                field: "attachments_count",
                title: "Images"
            },
            {
                field: 'rating',
                title: ' Rating',
                formatter: function (value, row, index) {
                    var rating = (value / 5) * 100;
                    return '<div class="star-ratings-sprite"><span style="width:' + rating + '%" class="star-ratings-sprite-rating"></span></div>';
                }
            },
            {
                field: "created_at",
                title: "Post Date",
                sortable: true
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

    $(document).on('change', 'input[type="checkbox"]', function (e) {
        var length = $('.bs-checkbox input[type="checkbox"]:checked').length;
        var button = $('#btn_delete');

        if (length === 0) {
            // disable the button
            button.prop('disabled', true);
        } else {
            // enable the button
            button.prop('disabled', false);
        }
    });

    $(document).on('click', '#btn_delete', function (e) {
        var selected = $('.selected');
        var ids = [];

        selected.each(function (index, obj) {
            ids.push($(obj).closest('tr').attr('data-uniqueid'));
        });

        BootstrapDialog.show({
            title: 'Delete feedback',
            message: 'Are you really want delete those comment?',
            animate: true,
            type: BootstrapDialog.TYPE_WARNING,
            buttons: [
                {
                    label: 'Yes',
                    cssClass: 'btn-success',
                    icon: 'glyphicon glyphicon-ok-sign',
                    action: function (dialogRef) {
                        dialogRef.close();

                        ajax(ajaxUrl,
                            {
                                _function: 'multiDelete',
                                ids: ids
                            },
                            function (response) {
                                if (response.status == 'success') {
                                    displaySuccess(response.data.message);
                                    $("#tb_comment").bootstrapTable('refresh');
                                    $('#btn_delete').prop('disabled', true);
                                } else {
                                    displayError(response.data.error);
                                    $("#tb_comment").bootstrapTable('refresh');
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
        })
    });

    $(document).on('click', '.action-toggle', function (e) {
        var id = $(this).closest('tr').attr('data-uniqueid');
        var label = $(this).closest('tr').find('span').first();
        var span = $(this).find('span');

        // add animation
        span.addClass('glyphicon-refresh-animate');

        ajax(ajaxUrl,
            {
                _function: 'toggle',
                id: id
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
                    $("#tb_comment").bootstrapTable('refresh');
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
        var id = tr.attr('data-uniqueid');

        BootstrapDialog.show({
            title: 'Delete feedback',
            message: 'Are you really want delete this comment?',
            animate: true,
            type: BootstrapDialog.TYPE_WARNING,
            buttons: [
                {
                    label: 'Yes',
                    cssClass: 'btn-success',
                    icon: 'glyphicon glyphicon-ok-sign',
                    action: function (dialogRef) {
                        dialogRef.close();

                        ajax(ajaxUrl, {_function: 'delete', id: id},
                            function (response) {
                                if (response.status == 'success') {
                                    $("#tb_comment").bootstrapTable('refresh');
                                } else {
                                    displayError(response.data.error);
                                    $("#tb_comment").bootstrapTable('refresh');
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
