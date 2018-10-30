@extends('admin')
@section('page_title', 'User Management')

@section('content-header')
    <h1>
        User Management
        <small>User</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/role/')}}"> User Management </a></li>
        <li class="active"> User</li>
    </ol>
@stop

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">User List</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div class="btn-group hidden-xs" id="TableEventsToolbar"></div>
                <table id="tb_user" class="display table table-bordered"></table>
            </div>
            <!-- /.box-body -->

        </div>
    </section>
@stop

@section('other-js' )
    <script src="{{url('js/user.js')}}"></script>
@endsection