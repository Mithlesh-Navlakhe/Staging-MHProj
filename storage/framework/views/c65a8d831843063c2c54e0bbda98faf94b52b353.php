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
   <!-- <link href="/css/app.css" rel="stylesheet">-->
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

<body id="login">
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"> <a href="#">Create User</a> </li>
                    <li class="dropdown"> <a href="#" data-toggle="dropdown" class="dropdown-toggle">Report <i class="caret"></i></a>
                        <ul class="dropdown-menu">
                            <li> <a tabindex="-1" href="#">Activity</a> </li>
                            <li> <a tabindex="-1" href="#">Dashboard</a> </li>
                        </ul>
                    </li>
                    <li class="dropdown"> <a href="manage-client.html" role="button" class="dropdown-toggle" data-toggle="dropdown">Manage <i class="caret"></i></a>
                        <ul class="dropdown-menu">
                            <li> <a tabindex="-1" href="manage-client.html">Clients</a> </li>
                            <li> <a tabindex="-1" href="#">Projects</a> </li>
                            <li> <a tabindex="-1" href="#">Tasks</a> </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo e(url('/logout')); ?>"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            Sing Out
                        </a>

                        <form id="logout-form" action="<?php echo e(url('/logout')); ?>" method="POST" style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<div class="alert alert-success"><a href="login.html"><img src="images/ignatiuz-logo.png" width="247" height="76" /></a></div>

<a href="#" data-toggle="tooltip" title="Текст всплывающей подсказки">

<div class="container">
    <div class="row-fluid">
        <div class="span12 heading-top-margin">

            <div class="heading-without-datepicker">Manage Client</div>
        </div>
    </div>


    <div class="row-fluid">
        <!-- block -->
        <div class="block-content collapse in">
            <div class="span12">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="focusedInput">Name</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput"  type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="focusedInput">Contact person</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput"  type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="focusedInput">Email</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput" placeholder="info@ignatiuz.com" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="focusedInput">Phone</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput" placeholder="+91-731-2970115" type="text">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="textarea2">Address</label>
                            <div class="controls">
                                <textarea class="input-xlarge textarea" placeholder="Enter text ..." style="width: 810px; height: 200px"></textarea>
                            </div>
                        </div>

                        <hr>
                        <div class="control-group">
                            <label class="control-label" for="focusedInput">Approval</label>
                            <div class="controls">
                                <label class="uniform">
                                    <input class="uniform_on manage-client" type="checkbox" id="optionsCheckbox" value="option1">

                                </label>
                                <span class="check-box-title"> Admin must approve track logs for this client </span>
                            </div>
                        </div>


                        <div class="form-actions">
                            <button type="submit" class="btn btn-large btn-primary" formaction="approval.html">Save</button> &nbsp;&nbsp;
                            <button type="submit" class="btn btn-large btn-primary" formaction="login.html">Cancel</button>
                        </div>

            </div>
        </div>
        <!-- /block -->
    </div>



</div>
<div class="navbar-inner ftr">
    <div>&copy; Ignatiuz</div>
</div>

<!-- /container -->
<script src="/assets/jquery-1.9.1.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>