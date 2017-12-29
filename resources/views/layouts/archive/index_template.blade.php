<?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ignatiuz</title>

    <!-- Styles -->
   <!-- <link href="/css/app.css" rel="stylesheet">-->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="/assets/styles.css" rel="stylesheet" media="screen">
    <link href="/css/main.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/assets/font-awesome.css">
    <link rel="" href="/assets/fonts/glyphicons-halflings-regular.eot">
    <link rel="stylesheet" href="/assets/DT_bootstrap.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body id="">
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <!--<li class=""> <a href="#}">Create User</a> </li>-->
                    <li class="dropdown"> <a href="#" data-toggle="dropdown" class="dropdown-toggle">Report <i class="caret"></i></a>
                        <ul class="dropdown-menu">
                            <li> <a tabindex="-1" href="#">A--</a> </li>
                            <li> <a tabindex="-1" href="#">--</a> </li>
                        </ul>
                    </li>
                    <li class="dropdown"> <a href="manage-client.html" role="button" class="dropdown-toggle" data-toggle="dropdown">Manage <i class="caret"></i></a>
                        <ul class="dropdown-menu">

                            @if ($status == 'Admin' || $status == 'HR Manager')
                            <li> <a tabindex="-1" href="/user/all">Users</a> </li>
                            @endif
                            <li> <a tabindex="-1" href="/team/all">Teams</a> </li>
                            <li> <a tabindex="-1" href="#">--</a> </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            Sign Out
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<div style=" border-bottom: solid 3px #808080">
    <div class="container"
        <div class="alert alert-success"><a href="{{ url('/') }}"><img src="{{ url('/images/ignatiuz-logo.png') }}" width="247" height="76" /></a></a>

            <div style="    display: inline-block;
            float: right;
                position: relative;
            margin-top: 71px;">
                <img src="{{ url('/images/log.png') }}"  width="60" height="60" style="position: absolute;
                left: -27px;
                bottom: -2px;"/>
                <span style="    display: inline-block;


                background-color: #808080;
                color: #ccc;
                padding: 10px 60px;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;">
                <strong>{{\Illuminate\Support\Facades\Auth::user()['original']['employe']}}</strong>
                <span>
            </div>
        </div>
    </div>
<div>

@yield('content')

<div class="navbar-inner ftr">
    <div>&copy; Ignatiuz</div>
</div>

<!-- /container -->
<script src="/assets/jquery-1.9.1.min.js"></script>

<script src="/assets/scripts.js"></script>


    <script src="/bootstrap/js/bootstrap.min.js"></script>

    <script src="/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="/assets/DT_bootstrap.js"></script>
<script>
    $(function() {

    });
</script>
</body>
</html>