<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <title>@yield('page_title') - Nan Tien Temple Mobile APP Backend</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @section('head-css')
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet"
              href="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css">
        <link rel="stylesheet"
              href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap-editable/css/bootstrap-editable.css">
        <link rel="stylesheet" href="{{url('css/AdminLTE.css')}}">
        <link rel="stylesheet" href="{{url('css/Custom.css')}}">
        <link rel="stylesheet" href="{{url('css/skins/_all-skins.min.css')}}">
    @show
    @section('other-css')
    @show
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    {{-- top navigation --}}
    @section('main-header')
    @show
    {{--/top navigation--}}

    {{--main navigation--}}
    @section('main-sidebar')
    @show
    {{--/main navigation--}}

    {{--main content--}}
    <div class="content-wrapper">
        <section class="content-header">
            @section('content-header')
            @show
        </section>
        <section class="content">
            @section('content')
            @show
        </section>
    </div>
    {{--/main content--}}

    {{--footer--}}
    @section('main-footer')
    @show
    {{--/footer--}}

    {{--right sidebar--}}
    @section('right-sidebar')
    @show
    {{--/right sidebar--}}
</div>

@section('head-js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table-locale-all.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/extensions/editable/bootstrap-table-editable.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.2.6/vue.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue-resource/1.3.1/vue-resource.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="{{url('js/app.js')}}"></script>
    <script src="{{url('js/system.js')}}"></script>
@show
{{-- include external js dependency --}}
@section('other-js')
@show
</body>
</html>
