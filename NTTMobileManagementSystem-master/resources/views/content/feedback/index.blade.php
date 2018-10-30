@extends('admin')
@section('page_title', 'Feedback Management')

@section('content-header')
    <h1>
        Content Management
        <small>Feedback</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/role/')}}"> Content Management </a></li>
        <li class="active"> Feedback</li>
    </ol>
@stop

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Feedback List</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div class="btn-group hidden-xs" id="TableEventsToolbar"></div>
                <table id="tb_feedback" class="display table table-bordered"></table>
            </div>
        </div>
    </section>
@stop

@section('other-js' )
    <script src="{{url('js/feedback.js')}}"></script>
@endsection
