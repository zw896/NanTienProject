/**
 * Created by Dafan Wang 2017/4/30.
 */

$(function () {
    var ajaxUrl = '/admin/role/admin/ajax';

    $("#tb_admin").bootstrapTable({
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
        toolbar: '#toolbar',
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
                field: "name",
                title: "Name",
                sortable: true,
                formatter: function (value, row, index) {
                    return '<a href="javascript:void(0)" class="edit-user">' + value + '</a>';
                }
            },
            {
                field: "last_login",
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
                // remove class
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
                                    $("#tb_admin").bootstrapTable('refresh');
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

    // edit user
    $(document).on('click', '.edit-user', function (e) {
        var uid = $(this).closest('tr').attr('data-uniqueid');
        var data = getRowData(uid);

        var form = cloneTable($("#register"));
        var modal = new BootstrapDialog({
            title: 'Edit account',
            message: form.css('display', 'block')
        });

        form.find('#inputName1').prop('defaultValue', data.name);
        form.find('#inputEmail1').val(data.email).prop('readonly', true);

        // validate name when lose focus
        form.on('blur', '#inputName1', function (e) {
            var name = $('#inputName1');
            var group = name.closest('.form-group');
            var newName = name.val().trim();

            if (newName != name.attr('value')) {
                ajax(ajaxUrl, {
                        _function: 'nameUnique',
                        name: newName
                    },
                    // success
                    function (response) {
                        if (response.status == 'success') {
                            group.removeClass('has-error').addClass('has-success');
                            group.find('.help-block').html('');
                        } else {
                            group.removeClass('has-success').addClass('has-error');
                            group.find('.help-block').html('<strong>' + response.data.error.name + '</strong>');
                        }
                    }
                )
            }
        });

        // bind the click action
        form.on('click', '#btn_submit', function (e) {
            // prevent default submit event.
            e.preventDefault();

            // validate input
            var name = $('#inputName1');
            var newName = name.val().trim();
            var nameChanged = false;

            if (newName != name.attr('value')) {
                nameChanged = true;
            }

            var password = $('#inputPassword1');
            var passwordChanged = false;

            if (password.val()) {
                var confirmPass = $('#inputPasswordConfirmation1');
                var pass_group = password.closest('.form-group');
                var cpass_group = confirmPass.closest('.form-group');

                if (confirmPass.val() != password.val()) {
                    pass_group.removeClass('has-success').addClass('has-error');
                    pass_group.find('.help-block').html('<strong>Conform Password Mismatch</strong>');
                    cpass_group.removeClass('has-success');

                    return false;
                } else {
                    pass_group.removeClass('has-error').addClass('has-success');
                    pass_group.find('.help-block').html('');
                    cpass_group.removeClass('has-error').addClass('has-success');
                    cpass_group.find('.help-block').html('');
                }

                passwordChanged = true;
            }

            ajax(ajaxUrl, {
                    _function: 'edit',
                    uid: uid,
                    name: (nameChanged) ? newName : null,
                    password: (passwordChanged) ? password.val() : null,
                    password_confirmation: (passwordChanged) ? confirmPass.val() : null
                },
                function (response) {
                    if (response.status != 'success') {
                        if (response.code == 400) {
                            var error = response.data.error;

                            if (error.name != null) {
                                group = name.closest('.form-group');
                                group.removeClass('has-success').addClass('has-error');
                                group.find('.help-block').html('<strong> ' + error.name + ' </strong>');
                            }

                            if (error.password != null) {
                                group = password.closest('.form-group');
                                group.removeClass('has-success').addClass('has-error');
                                group.find('.help-block').html('<strong> ' + error.password + ' </strong>');
                            }

                        } else {
                            displayError(response.data.error);
                            modal.close();
                        }
                    } else {
                        displaySuccess("Successfully modified the account.");
                        $("#tb_admin").bootstrapTable('refresh');
                        modal.close();
                    }
                }
            )
        });

        modal.open();
    });

    // add a new user
    $('#add').on('click', function () {
        var form = cloneTable($("#register"));
        var modal = new BootstrapDialog({
            title: 'Add a new account',
            message: form.css('display', 'block')
        });

        // bind the submit event
        form.on('click', '#btn_submit', function (e) {
            // prevent default submit event.
            e.preventDefault();

            var field = {
                Name: $('#inputName1'),
                Email: $('#inputEmail1'),
                Password: $('#inputPassword1'),
                'Conform Password': $('#inputPasswordConfirmation1')
            };
            var hasError = false;
            var group;

            // check not empty
            for (var key in field) {
                var value = field[key];

                group = value.closest('.form-group');
                // Check if there is an entered value
                if (!value.val()) {
                    // Add errors highlight
                    group.removeClass('has-success').addClass('has-error');
                    group.find('.help-block').html('<strong>' + key + ' Cannot be empty </strong>');

                    // Stop submission of the form
                    hasError = true;
                } else {
                    // Remove the errors highlight
                    group.removeClass('has-error').addClass('has-success');
                    group.find('.help-block').html('');
                }
            }

            // check password
            if (field['Password'].val()) {
                group = field['Conform Password'].closest('.form-group');
                if (field['Password'].val() !== field['Conform Password'].val()) {
                    group.removeClass('has-success').addClass('has-error');
                    group.find('.help-block').html('<strong>Conform Password Mismatch</strong>');
                    hasError = true;
                } else {
                    // Remove the errors highlight
                    group.removeClass('has-error').addClass('has-success');
                    group.find('.help-block').html('');
                }
            } else {
                group.removeClass('has-success');
            }

            //validate email
            if (field['Email'].val()) {
                group = field['Email'].closest('.form-group');
                if (!validateEmail(field['Email'].val())) {
                    hasError = true;
                    group.removeClass('has-success').addClass('has-error');
                    group.find('.help-block').html('<strong>Invalid Email Format</strong>');
                } else {
                    group.removeClass('has-error').addClass('has-success');
                    group.find('.help-block').html('');
                }
            }

            if (!hasError) {
                $.ajax({
                        type: "POST",
                        url: ajaxUrl,
                        dataType: 'json',
                        data: {
                            _function: 'register',
                            name: field['Name'].val(),
                            email: field['Email'].val(),
                            password: field['Password'].val(),
                            password_confirmation: field['Conform Password'].val()
                        },
                        success: function (response) {

                            if (response.status != 'success') {
                                if (response.code == 400) {
                                    var error = response.data.error;

                                    if (error.email != null) {
                                        group = field['Email'].closest('.form-group');
                                        group.removeClass('has-success').addClass('has-error');
                                        group.find('.help-block').html('<strong> ' + error.email + ' </strong>');
                                    }

                                    if (error.name != null) {
                                        group = field['Name'].closest('.form-group');
                                        group.removeClass('has-success').addClass('has-error');
                                        group.find('.help-block').html('<strong> ' + error.name + ' </strong>');
                                    }

                                    if (error.password != null) {
                                        group = field['Password'].closest('.form-group');
                                        group.removeClass('has-success').addClass('has-error');
                                        group.find('.help-block').html('<strong> ' + error.password + ' </strong>');
                                    }

                                } else {
                                    displayError(response.data.error);
                                    modal.close();
                                }
                            } else {
                                displaySuccess("Successfully added an account.");
                                $("#tb_admin").bootstrapTable('refresh');
                                modal.close();
                            }
                        },
                        error: function (response) {
                            displayError('Internal error.');
                        }
                    }
                )
            }
        });

        modal.open();
    });

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function getRowData(rowID) {
        var data = $("#tb_admin").bootstrapTable('getData', {useCurrentPage: false});
        var row = null;

        data.forEach(function (p1, p2, p3) {
            if (p1.id == rowID) {
                row = p1;
                return;
            }
        });

        return row;
    }
});
