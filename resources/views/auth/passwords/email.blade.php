@extends('layouts.template')

<!-- Main Content -->
@section('content')
<div class="container">
    @if ( isset($loginStatus))

        <strong>{{ $loginStatus }}</strong>

    @endif
    <div class="row" id="login">

        <form class="form-signin" role="form" method="POST" style="min-height: 220px;" action="{{ url('/password/email') }}">
            <h2 class="form-signin-headin">Reset Password</h2>
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="usericon"><i class="fa fa-key"></i></div>
                <input id="email" type="email" placeholder="E-Mail Address" class="input-block-level" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                @if (session('status'))
                    <div class="help-block" style="color:color: #468847">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="col-md-6">
                <a href="/" class="btn btn-large btn-primary">Back</a>
                    </div>
                <div class="col-md-6">
                <button  type="submit" class="btn btn-large btn-primary">
                    Send Password Reset Link
                </button>
                    </div>
            </div>

        </form>

    </div>
</div>
@endsection
