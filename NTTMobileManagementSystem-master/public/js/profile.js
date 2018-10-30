/**
 * Created by Dafan Wang on 2017/4/30.
 */

$(function () {
    var ajaxUrl = '/admin/role/user/profile/ajax';
    var commentAjaxUrl = '/admin/content/comment/ajax';
    var hasMore = true;

    function checkHasMore() {
        // check has more
        var button = $('#loadMore');
        var loaded = button.data('loaded');
        var uid = button.data('uid');

        ajax(ajaxUrl,
            {
                _function: 'hasMoreComment',
                uid: uid,
                loaded: loaded
            },
            function (response) {
                if (response.status == 'success') {
                    hasMore = response.data.hasMore;
                    if (!response.data.hasMore) {
                        button.attr('disabled', true);
                    }
                } else {
                    displayError(response.data.error);
                }
            }
        );
    }

    $(document).on('click', '#loadMore', function (e) {
        var button = $('#loadMore');
        var loaded = button.data('loaded');
        var uid = getUserID();
        var icon = button.find('i');

        button.attr('disabled', true);
        icon.addClass('glyphicon-refresh-animate');

        ajax(ajaxUrl,
            {
                _function: 'loadMore',
                uid: uid,
                loaded: loaded
            },
            function (response) {
                if (response.status == 'success') {
                    button.data('loaded', loaded + response.data.count);
                    var img = $('div .profile-user-img').attr('src');

                    var content = "";
                    $(response.data.comments).each(function (index, value) {
                        content += '<div class="post"><div class="user-block"><img class="img-circle img-bordered-sm" src="' + img + '" alt="user image"><span class="username"><a href="#">';
                        content += value.event.title + '</a><a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a></span><span class="description">Replied at - ' + value.created_at;
                        content += '</span></div><p>' + value.content + '</p> <ul class="list-inline" id="comment_' + value.id + '"> <li>';
                        if (value.display) {
                            content += '<a href="javascript:void(0)" class="link-black text-sm toggle_comment"><i class="fa fa-check margin-r-5"></i>Approval</a>';
                        } else {
                            content += '<a href="javascript:void(0)" class="link-black text-sm toggle_comment"><i class="fa fa-times-circle margin-r-5"></i>Disapproval</a>';
                        }

                        content += '</li><li><a href="javascript:void(0)" class="link-black text-sm delete_comment"><i class="fa fa-trash-o margin-r-5"></i>Delete</a></li></ul>';
                        content += '<input class="form-control input-sm" type="text" placeholder="Type a comment"></div>';
                    });

                    $('div .post').last().after(content);
                } else {
                    displayError(response.data.error);
                }
            },
            null,
            function () {
                // remove animation
                icon.removeClass('glyphicon-refresh-animate');
                button.removeAttr('disabled');
                checkHasMore();
            }
        );
    });

    $(document).on('click', '.toggle_comment', function (e) {
        var t = $(this);
        t.fadeOut('fast');
        t.parent().append('<i class="fa fa-refresh glyphicon-refresh-animate"></i>');

        ajax(commentAjaxUrl,
            {
                _function: 'toggle',
                id: findCommentID(this)
            },
            function (response) {
                if (response.status == 'success') {
                    if (t.children('i').hasClass('fa-check')) {
                        t.html(function () {
                            return $(this).html().replace('Approval', 'Disapproval');
                        });

                        t.children('i').removeClass('fa-check').addClass('fa-times-circle');
                    } else {
                        t.html(function () {
                            return $(this).html().replace('Disapproval', 'Approval');
                        });

                        t.children('i').removeClass('fa-times-circle').addClass('fa-check');
                    }
                } else {
                    displayError(response.data.error);
                }
            },
            null,
            function () {
                t.fadeIn('slow');
                t.parent().find('.glyphicon-refresh-animate').remove();
            }
        )
    });

    $(document).on('click', '.delete_comment', function (e) {
        var cid = findCommentID(this);
        var button = $('#loadMore');
        var loaded = button.data('loaded');
        var t = $(this);

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

                        ajax(commentAjaxUrl,
                            {
                                _function: 'delete',
                                id: cid
                            },
                            function (response) {
                                if (response.status == 'success') {
                                    button.data('loaded', loaded - 1);
                                    t.closest('div').fadeOut('slow');

                                    if (hasMore && button.data('loaded') < 5) {
                                        button.trigger('click');
                                    }
                                } else {
                                    displayError(response.data.error);
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

    $(document).on('click', '#btn_setting', function (e) {
        e.preventDefault();
        var id = getUserID();

        // do ajax submit
        ajax(ajaxUrl,
            {
                _function: 'profileUpdate',
                id: id,
                email: email,
                gender: gender
            },
            function (response) {
                if (response.status == 'success') {
                    displaySuccess('successfully updated information');
                } else {
                    displayError(response.data.error);
                }
            }
        )
    });

    $(document).on('click', '#btn_password', function (e) {
        e.preventDefault();

        var id = getUserID();

        // do ajax submit
        ajax(ajaxUrl,
            {
                _function: 'updatePassword',
                id: id,
                password: password
            },
            function (response) {
                if (response.status == 'success') {
                    displaySuccess('successfully updated information');
                } else {
                    displayError(response.data.error);
                }
            }
        )
    });

    function findCommentID(a) {
        return $(a).closest('ul').attr('id').replace('comment_', '');
    }

    function getUserID() {
        return $('#uid').data('uid');
    }
});
