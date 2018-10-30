@extends('admin')
@section('page_title', 'User Profile')

@section('content-header')
    <h1>
        User Profile
        <small>User</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i>Main</a></li>
        <li class="active"><a href="{{url('/admin/role/')}}"> User Management </a></li>
        <li class="active"> User Profile</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle"
                         src="{{\App\Components\Helpers\GravatarHelper::get($user->email)}}"
                         alt="User profile picture">


                    <h3 class="profile-username text-center">{{$user->username}}</h3>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Joined</b> <a class="pull-right">{{$user->created_at}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Gender</b> <a class="pull-right">{{$user->gender}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Login</b> <a class="pull-right">{{$user->updated_at}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Register IP</b> <a class="pull-right">{{$user->register_ip}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Login IP</b> <a class="pull-right">{{$user->login_ip}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Banned</b> <a class="pull-right">{{$user->isBanned() ? 'true' : 'false'}}</a>
                        </li>
                    </ul>

                    @if($user->isBanned())
                        <a href="#" class="btn btn-primary btn-block"><b>Unban</b></a>
                    @else
                        <a href="#" class="btn btn-danger btn-block"><b>Ban</b></a>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div id="uid" data-uid="{{$user->id}}"></div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab">Event Comment</a></li>
                    <li><a href="#feedback" data-toggle="tab">Feedback</a></li>
                    <li><a href="#settings" data-toggle="tab">Settings</a></li>
                    <li><a href="#password" data-toggle="tab">Password</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                    @if (!$comments->isEmpty())

                        @foreach($comments as $comment)
                            <!-- Post -->
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm"
                                             src="{{\App\Components\Helpers\GravatarHelper::get($user->email)}}"
                                             alt="user image">
                                        <span class="username"><a href="#">{{$comment->event->title}}</a><a href="#"
                                                                                                            class="pull-right btn-box-tool"><i
                                                        class="fa fa-times"></i></a></span>
                                        <span class="description">Replied at - {{$comment->created_at}}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        {{$comment->content}}
                                    </p>
                                    <ul class="list-inline" id="comment_{{$comment->id}}">
                                        <li>
                                            @if($comment->display)
                                                <a href="javascript:void(0)"
                                                   class="link-black text-sm toggle_comment"><i
                                                            class="fa fa-check margin-r-5"></i>Approval</a>
                                            @else
                                                <a href="javascript:void(0)"
                                                   class="link-black text-sm toggle_comment"><i
                                                            class="fa fa-times-circle margin-r-5"></i>Disapproval</a>
                                            @endif
                                        </li>
                                        <li><a href="javascript:void(0)" class="link-black text-sm delete_comment"><i
                                                        class="fa fa-trash-o margin-r-5"></i>
                                                Delete</a>
                                        </li>
                                    </ul>

                                    <input class="form-control input-sm" type="text" placeholder="Type a comment">
                                </div>
                        @endforeach
                        <!-- load more -->
                            <div class=" text-center">
                                <button id="loadMore" data-loaded="{{$comment_count}}"
                                        class="btn btn-default btn-block">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> Load More
                                </button>
                            </div>
                        @else
                            <div class="post">
                                <p>
                                    Nothing found.
                                </p>
                            </div>
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="feedback">
                    @if(!$user->feedback->isEmpty())
                        <!-- The timeline -->
                            <ul class="timeline timeline-inverse">
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-comments bg-yellow"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post
                                        </h3>

                                        <div class="timeline-body">
                                            Take me to your leader!
                                            Switzerland is small and neutral!
                                            We are more like Germany, ambitious and misunderstood!
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                            </ul>
                        @else
                            <div>
                                <p>
                                    Nothing found.
                                </p>
                            </div>
                        @endif
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">User Name</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputName" placeholder="Name"
                                           value="{{$user->username}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="Email"
                                           value="{{$user->email}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputGender" class="col-sm-2 control-label">Gender</label>

                                <div class="col-sm-2">
                                    <select id="inputGender" class="form-control">
                                        <option value="0" {{$user->gender == 0 ? 'selected' : ''}}>Unknown</option>
                                        <option value="1" {{$user->gender == 1 ? 'selected' : ''}}>Male</option>
                                        <option value="2" {{$user->gender == 2 ? 'selected' : ''}}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger" id="btn_setting">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="password">
                        <form class="form-horizontal" action="" method="post">

                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputPassword" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPasswordConfirmation" class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputPasswordConfirmation"
                                           placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger" id="btn_password">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop

@section('other-js')
    <script src="{{url('js/profile.js')}}"></script>
@endsection