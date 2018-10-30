@extends('admin')
@section('page_title', 'Event Management')

@section('content-header')
    <h1>
        Attachment Management
        <small>Images</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/attachment/')}}"> Attachment </a></li>
        <li class="active"> Images</li>
    </ol>
@stop

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Images List</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div class="btn-group hidden-xs" id="TableEventsToolbar"></div>
                <table id="tb_image" class="display table table-bordered"></table>
            </div>
            <!-- /.box-header -->
        </div>
    </section>
@stop

@section('other-js' )
    <script src="{{url('js/image.js')}}"></script>
@endsection