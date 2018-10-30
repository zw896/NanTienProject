@extends('admin')
@section('page_title', 'Edit Comment')

@section('other-css')
    <link href="//cdn.bootcss.com/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@endsection
@section('other-js')
    <script src="{{url('admin/js/tinymce/tinymce.min.js')}}"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            height: 500,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
            image_advtab: true,
            templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    </script>
@endsection
@section('content-header')
    <h1>
        Content Management
        <small>Events</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/content/')}}"> Content </a></li>
        <li class="active"><a href="{{url('/admin/content/event')}}"> Comment </a></li>
        <li class="active"> Edit</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Comment</h3>
        </div>

        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <form method="POST" action="#" accept-charset="utf-8">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Main</a></li>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_1">
                            <div class="form-group">
                                <label>Title
                                    <small class="text-red">*</small>
                                </label>
                                <input required="required" type="text" class="form-control" name="title"
                                       autocomplete="off"
                                       placeholder="title" maxlength="255" value="{{$event->title}}">
                            </div>
                            <div class="form-group">
                                <label>Author
                                    <small class="text-red">*</small>
                                </label>
                                <input required="required" type="text" class="form-control" name="owner"
                                       autocomplete="off"
                                       placeholder="author" maxlength="80" value="{{$event->author}}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Sticky
                                    <small class="text-red">*</small>
                                </label>
                                <select class="js-example-placeholder-single form-control">
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Body
                                    <small class="text-red">*</small>
                                </label>
                                <div id="editormd_id">
                                    <textarea name="body">{{$event->body}}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
