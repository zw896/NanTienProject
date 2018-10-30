@extends('admin')
@section('page_title', 'Admin Management')

@section('content-header')
    <h1>
        User Management
        <small>Admin</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/role/')}}"> User Management </a></li>
        <li class="active"> Admin</li>
    </ol>
@stop

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Admin List</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <form id="register" style="display: none;">
                    <div class="form-group">
                        <div class="col-md-12">
                            <span class="help-block col-md-8 pull-right"></span>
                        </div>
                        <div class="input-group col-md-12">
                            <label for="inputName0" class="col-sm-4 control-label">Name <i>*</i></label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" id="inputName0"
                                       placeholder="Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <span class="help-block col-md-8 pull-right"></span>
                        </div>
                        <div class="input-group col-md-12">
                            <label for="inputEmail0" class="col-sm-4 control-label">Email <i>*</i></label>
                            <div class="col-sm-8">
                                <input name="email" type="email" class="form-control" id="inputEmail0"
                                       placeholder="Email" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <span class="help-block col-md-8 pull-right"></span>
                        </div>
                        <div class="input-group col-md-12">
                            <label for="inputPassword0"
                                   class="col-sm-4 control-label">Password<i>*</i></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="inputPassword0"
                                       placeholder="Password" name="password" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <span class="help-block col-md-8 pull-right"></span>
                        </div>
                        <div class="input-group col-md-12">
                            <label for="inputPasswordConfirmation0" class="col-sm-4 control-label">Conform
                                Password<i>*</i></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="inputPasswordConfirmation0"
                                       placeholder="Password" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group col-md-12">
                            <label class="control-label pull-right"><i>*</i> Indicates required field</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group col-md-12">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-info" id="btn_submit">Submit</button>
                            </div>
                            <div class="pull-right col-md-2">
                                <button type="submit" class="btn btn-info" id="btn_cancel"
                                        data-dismiss="modal">Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div id="toolbar">
                    <button id="add" class="btn btn-primary"><i
                                class="glyphicon glyphicon glyphicon-user"></i> Add
                    </button>
                </div>
                <div class="btn-group hidden-xs" id="TableEventsToolbar"></div>
                <table id="tb_admin" class="display table table-bordered"></table>

            </div>
            <!-- /.box-body -->
        </div>
    </section>
@stop

@section('other-js' )
    <script src="{{url('js/admin.js')}}"></script>
@endsection