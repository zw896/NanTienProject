@extends('layouts.auth')

@section('page_title', 'Registration Page')
@section('body_class', 'register-page')
@section('main-content')
    <div class="login-box">
        <div class="login-logo">
            <a href="javascript:void(0)"><b>NTT Mobile</b> Backend</a>
        </div>
        <!-- /.login-logo -->
        <div class="register-box-body">
            <p class="login-box-msg">Register a new account</p>

            <form role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <input name="name" type="text" class="form-control" placeholder="Full name" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input name="email" type="email" class="form-control" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm password"
                           name="password_confirmation" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>

                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="{{ route('login') }}" class="text-center">I already have an account</a>
        </div>
        <!-- /.login-box-body -->
    </div>
@stop
