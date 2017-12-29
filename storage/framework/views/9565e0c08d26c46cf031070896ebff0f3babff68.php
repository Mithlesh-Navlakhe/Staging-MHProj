<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Ignatiuz</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="/assets/styles.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/assets/font-awesome.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="app">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php if(Auth::guest()): ?>
                           <!-- <li><a href="<?php echo e(url('/login')); ?>">Login</a></li>
                            <li><a href="<?php echo e(url('/register')); ?>">Register</a></li>-->
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="<?php echo e(url('/logout')); ?>"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="<?php echo e(url('/logout')); ?>" method="POST" style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>
   <!-- <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">


                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>


                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    <img src="images/ignatiuz-logo.png" width="247" height="76" />
                </a>
            </div>
        </div>
    </nav>-->
    <div class="alert alert-success"><a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('/images/ignatiuz-logo.png')); ?>" width="247" height="76" /></a></div>

    <?php echo $__env->yieldContent('content'); ?>
</div>

<div class="navbar-inner ftr">
    <div>&copy; Ignatiuz</div>
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
</body>
</html>