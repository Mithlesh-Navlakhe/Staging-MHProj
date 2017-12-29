@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row" id = "login">

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-signin" role="form" method="POST" action="{{ url('/password/reset') }}" style="padding-bottom: 46px;">
                <h2 class="form-signin-heading"><i class="fa-li fa"></i>Reset Password</h2>
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="usericon"><i class="fa fa-envelope"></i></div>
                    <input id="email" type="email" class="input-block-level" placeholder="E-Mail Address" name="email" value="{{ $email or old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="usericon"><i class="fa fa-key"></i></div>
                    <input id="password" type="password" class="input-block-level" placeholder="Password" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <div class="usericon"><i class="fa fa-key"></i></div>
                    <input id="password-confirm" type="password" class="input-block-level" placeholder="Confirm Password" name="password_confirmation" required>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif

                    <button type="submit" class="btn btn-large btn-primary">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
