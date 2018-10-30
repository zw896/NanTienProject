@extends('layouts.admin')

{{--top menu--}}
@section('main-header')
    <header class="main-header">
        <a href="{{url('admin/dashboard')}}" class="logo">
            <span class="logo-mini">Mobile APP Backend</span>
            <span class="logo-lg"><b>Mobile APP Backend</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <a href="{{url('admin/dashboard')}}">
                            <i class="fa fa-home"></i>
                            <span class="label label-info">H</span>
                        </a>
                    </li>
                    <li class="dropdown notifications-menu">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            @if($message['count'] > 0)
                                <span class="label label-warning">{{$message['count'] > 100 ? '100+' : $message['count'] }}</span>
                            @endif
                        </a>
                        @if($message['count'] > 0)
                            <ul class="dropdown-menu">
                                <li>
                                    @foreach($message['data'] as $data)
                                        <ul class="menu">
                                            <li>
                                                <a href="{{$data['url']}}">{{$data['title']}}
                                                    <i class="fa fa-pull-right fa-times-circle message-dismiss"
                                                       aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    @endforeach
                                </li>
                                @if($message['count'] > 5)
                                    <li class="footer"><a href="{{url('/admin/message/')}}">More</a></li>
                                @endif
                            </ul>
                        @endif
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{\App\Components\Helpers\GravatarHelper::get(Auth::user()->email)}}"
                                 class="user-image" alt="User Image">
                            <span class="hidden-xs"> {{ Auth::user()->name }} </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{\App\Components\Helpers\GravatarHelper::get(Auth::user()->email)}}"
                                     class="img-circle" alt="User Image">

                                <p>
                                    Welcome to the backend.
                                    <small>Last login: {{ Auth::user()->last_login }}</small>
                                </p>
                            </li>
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="javascript:void(0)">PHP</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="javascript:void(0)">VUEJS</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="javascript:void(0)">C++</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="javascript:void(0)" class="btn btn-default btn-flat">Edit</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">{{ csrf_field() }}</form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
@endsection
{{--/top navbar--}}
{{--main menu--}}
@section('main-sidebar')
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{\App\Components\Helpers\GravatarHelper::get(Auth::user()->email)}}"
                         class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::User()->name }}</p>
                    <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">Main Navigation</li>
                <li>
                    <a href="{{url('admin/dashboard')}}">
                        <i class="fa fa-dashboard"></i> <span>Control Panel</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-user"></i>
                        <span>User Management</span>
                        <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{action('Admin\AdminManagementController@adminList')}}"><i
                                        class="fa fa-circle-o"></i>Admin</a></li>
                        <li><a href="{{action('Admin\UserManagementController@userList')}}"><i
                                        class="fa fa-circle-o"></i>User</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-book"></i>
                        <span>Content Management</span>
                        <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{action('Admin\ContentManagementController@getEventList')}}"><i
                                        class="fa fa-circle-o"></i>Upcoming Event</a></li>
                        <li><a href="{{action('Admin\CommentManagementController@getCommentList')}}"><i
                                        class="fa fa-circle-o"></i>Comment</a></li>
                        <li><a href="{{action('Admin\FeedbackManagementController@getFeedbackList')}}"><i
                                        class="fa fa-circle-o"></i>Feedback</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-paperclip"></i>
                        <span>Attachment Management</span>
                        <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{action('Admin\ImageManagementController@getPage')}}"><i
                                        class="fa fa-circle-o"></i>Images</a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-circle-o"></i>Videos</a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-cogs"></i>
                        <span>System Settings</span>
                        <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="javascript:void(0)"><i class="fa fa-circle-o"></i>General</a></li>
                        <li><a href="{{action('Admin\SystemSettingsController@getFieldDefinition')}}"><i
                                        class="fa fa-circle-o"></i>Field</a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-circle-o"></i>Api Access</a></li>
                    </ul>
                </li>
                <li class="header">LABELS</li>
                <li><a href="javascript:void(0)"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
                <li><a href="javascript:void(0)"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a>
                </li>
                <li><a href="javascript:void(0)"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
@endsection
{{--/Main navbar--}}

{{--footer--}}
@section('main-footer')
    <footer class="main-footer">
        <div class="pull-right hidden-xs">Theme By <a href="https://almsaeedstudio.com/" target="_blank">AdminLTE</a>.
        </div>
        <strong>Copyright © 2017 <a href="http://www.digital5.net/" target="_blank">Digital 5</a>.</strong> All rights
        reserved. &nbsp;&nbsp;
    </footer>
@endsection
{{--/footer--}}
