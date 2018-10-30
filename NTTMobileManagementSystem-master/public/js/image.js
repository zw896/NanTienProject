/**
 * Created by Dafan Wang on 2017/4/29.
 */
$(function () {
    var ajaxUrl = '/admin/attachment/image/ajax';

    $("#tb_image").bootstrapTable({
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
                field: "filename",
                title: "Filename",
                sortable: true,
                formatter: function (value) {
                    var url = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/';
                    return '<a rel="nofollow" rel="noreferrer" class="screenshot" href="javascript:void(0)" data-src="' + url + 'storage/attachments/images/' + value + '">' + value + '</a>';
                }
            },
            {
                field: 'comment.id',
                title: "Comment ID"
            },
            {
                field: 'comment.event.title',
                title: "Event"
            },
            {
                field: "comment.user.username",
                title: "Author"
            },
            {
                field: "size",
                title: "Size",
                sortable: true
            },
            {
                field: "created_at",
                title: "Publish Date",
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
        }
    });

    $(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        var aid = tr.attr('data-uniqueid');

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

                        ajax(ajaxUrl,
                            {
                                _function: 'delete',
                                id: aid
                            },
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

    /*
     * Url preview script
     * powered by jQuery (http://www.jquery.com)
     *
     * written by Alen Grakalic (http://cssglobe.com)
     * edited by Daan van der Zwaag (https://dvdz.design)
     *
     */

    function previewImages() {
        /* CONFIG */

        var xOffset = 200;
        var yOffset = 80;

        // these 2 variable determine popup's distance from the cursor
        // you might want to adjust to get the right result

        /* END CONFIG */
        $(document).on({
            mouseenter: function (e) {
                //stuff to do on mouse enter
                var $this = $(this); // caching $(this)

                $("body").append("<div id='previewImage'><img src='" + $this.data('src') + "' alt='rens preview image' height='80' />" + "</div>");

                $("#previewImage")
                    .css("top", (e.pageY - xOffset) + "px")
                    .css("left", (e.pageX + yOffset) + "px")
                    .stop(true, true)
                    .fadeIn("fast");
            },
            mouseleave: function (e) {
                //stuff to do on mouse leave
                var $this = $(this); // caching $(this)
                $this.text($this.data('initialText'));

                $("#previewImage").remove();
            },
            mousemove: function (e) {
                $("#previewImage")
                    .css("top", (e.pageY - xOffset) + "px")
                    .css("left", (e.pageX + yOffset) + "px");
            }

        }, "a.screenshot"); //pass the element as an argument to .on
    }

    previewImages();
});

