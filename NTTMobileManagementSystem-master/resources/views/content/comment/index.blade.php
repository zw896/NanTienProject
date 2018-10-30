@extends('admin')
@section('page_title', 'Comment Management')

@section('content-header')
    <h1>
        Content Management
        <small>Comment</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/role/')}}"> Content Management </a></li>
        <li class="active"> Comment</li>
    </ol>
@stop

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Comment List</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div id="toolbar">
                    <button id="btn_delete" class="btn btn-danger" disabled><i class="glyphicon glyphicon-remove"></i>
                        Delete
                    </button>
                </div>
                <table id="tb_comment" class="display table table-bordered"></table>
            </div>
        </div>
    </section>
@stop

@section('other-js' )
    <script src="{{url('js/comment.js')}}"></script>
@endsection