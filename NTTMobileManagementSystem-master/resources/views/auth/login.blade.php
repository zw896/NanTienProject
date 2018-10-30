@extends('layouts.auth')

@section('page_title', 'Login')
@section('body_class', 'login-page')
@section('main-content')
    <div class="login-box">
        <div class="login-logo">
            <a href="javascript:void(0)"><b>Mobile App</b> Backend</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in</p>

            <form role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                @if ($errors->has('login'))
                    <span class="help-block" style="color: red;"><strong>{{ $errors->first('login') }}</strong></span>
                @endif

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false"
                                     style="position: relative;"><input type="checkbox" name="remember"
                                                                        {{ old('remember') ? 'checked' : '' }} style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                </div>
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            @if(\App\Components\Helpers\OptionHelper::get('allow_register') !== 'false')
                <a href="{{ route('register') }}" class="text-center">Register a new account</a>
            @endif
        </div>
        <!-- /.login-box-body -->
    </div>
@stop
