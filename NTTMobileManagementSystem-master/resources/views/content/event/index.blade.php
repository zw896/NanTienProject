@extends('admin')
@section('page_title', 'Event Management')

@section('content-header')
    <h1>
        Content Management
        <small>Event</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/content/')}}"> Content </a></li>
        <li class="active"> Event</li>
    </ol>
@stop

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Event List</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div class="btn-group hidden-xs" id="TableEventsToolbar"></div>
                <table id="tb_event" class="display table table-bordered"></table>
            </div>
            <!-- /.box-header -->
        </div>
    </section>
@stop

@section('other-js' )
    <script src="{{url('js/event.js')}}"></script>
@endsection