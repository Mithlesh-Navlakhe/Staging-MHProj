<?php $status = \Illuminate\Support\Facades\Auth::user()['original']['employe'] ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ignatiuz</title>
    <link href="/css/main.css" rel="stylesheet" media="screen">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png">
    <link href="/css/jquery.jgrowl.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/assets/font-awesome.css">
    <link rel="" href="/assets/fonts/glyphicons-halflings-regular.eot">
    <link href="/bootstrap3/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="/bootstrap3/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
    <link href="/bootstrap3/css/bootstrap-theme.css.map" rel="stylesheet" media="screen">
    <link href="/css/bootstrap-datetimepicker.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/css/application.css" />
    <link rel="stylesheet" href="/css/calendar-range.css" />
    <link rel="stylesheet" type="text/css" href="/datatables/DataTables-1.10.12/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/datatables/Buttons-1.2.2/css/buttons.bootstrap.min.css"/>
	
	<link rel="stylesheet" type="text/css" href="/css/common.css">  <!--- SN 07/27/2017: added common.css and common-mobile.css --->
	<link rel="stylesheet" type="text/css" href="/css/common-mobile.css"> 
	<link rel="stylesheet" type="text/css" href="/css/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery.timepicker.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery.timepicker.min.css">
    <script type="text/javascript" src="/datatables/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
    <script src="/js/moment.js"></script>
    <!-- Scripts -->
    <script>
        window.Laravel = '<?php echo json_encode(['csrfToken' => csrf_token(), ]); ?>'
    </script>
</head>
<body id="bodyData" data-msg="<?=isset($msg) ? $msg : '' ?>" data-theme="<?=isset($theme) ? $theme : '' ?>">
<div class="navbar-inner navbar-style">
    <nav class="navbar navbar-top nav-my pull-right">
        <div class="container-fluid container-height" >
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div>
                <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse open-menu" >
                    <ul class="nav navbar-nav navbar-right navbar-submenu margin-right-twenty" >
					<!-- SN 05/24/2017: added below menu - dashboard -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle menuFirst" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
						   onclick="event.preventDefault();   window.location = '/dashboard';">
							Dashboard
						</a>
					</li>
					
                    <?php if($status != 'HR Manager'): ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle menuFirst" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
							   onclick="event.preventDefault();   window.location = '/tracking';">
								TRACK
							</a>
						</li>
                    <?php endif; ?>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle menuFirst" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Report <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-my  dropdown-menu-style" >
                                <li><a href="/reports/daily">Daily Report</a></li>
                                <?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Supervisor'): ?>
									<li><a href="/reports/project">Project Report</a></li>
									<li><a href="/reports/people">People Report</a></li>
								<?php endif; ?>
								<?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor'): ?>
								  <li><a href="/reports/performance">Performace Report</a></li>
								<?php endif; ?>
								<?php if($status == 'Lead' || $status == 'Super Admin' || $status == 'Admin' || $status == 'Supervisor'): ?>
									<li><a href="/reports/emailproject">Email Report</a></li>     <!-- Mith: 05/10/17: added new option for email report. -->
								<?php endif; ?>
								<!--<?php if($status == 'Super Admin' || $status == 'Admin'): ?>
									<li role="separator" class="divider"></li>
                                <?php endif; ?> -->
                            </ul>
                        </li>


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle menuFirst" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                Manage <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-my dropdown-menu-style">
                                <?php if($status == 'Admin' || $status == 'HR Manager' || $status == 'Super Admin'): ?>
                                    <li> <a tabindex="-1" href="/user/all">Users</a> </li>
                                <?php endif; ?>
                                <?php if($status == 'Super Admin'): ?>
                                    <li> <a tabindex="-1" href="/client/all">Clients</a> </li>
                                <?php endif; ?>
                                <?php if($status == 'Super Admin'): ?>
                                    <li> <a tabindex="-1" href="/project/all">Projects</a> </li>
                                <?php endif; ?>
                                <?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Supervisor' || $status == 'Lead' || $status == 'Developer' || $status == 'QA Engineer'): ?>
                                    <li> <a tabindex="-1" href="/task/all">Tasks</a> </li>
                                <?php endif; ?>
                                <?php if($status == 'Super Admin' || $status == 'Admin'): ?>
                                    <li> <a tabindex="-1" href="/task-type/all">Task Types</a> </li>
									                  <li> <a tabindex="-1" href="/task-status/all/">Task Status</a> </li>
                                <?php endif; ?>
                                <?php if($status == 'Super Admin' || $status == 'Admin'): ?>
                                    <li> <a tabindex="-1" href="/team/all">Teams</a> </li>
                                <?php endif; ?>
								<li role="separator" class="divider"></li>
                                <li> <a tabindex="-1" href="/track/all">Time Tracks</a> </li>
								<?php if($status == 'Super Admin' || $status == 'Admin' || $status == 'Lead' || $status == 'Supervisor'): ?>
                                    <li> <a tabindex="-1" href="/task/archive">Task Archive</a> </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li class="dropdown">
							<!--
                            <a href="#" class="dropdown-toggle menuFirst" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" onclick="event.preventDefault();
                               location.replace('/user/logout')">
                                Sign Out
                            </a> -->
							<a href="#" class="dropdown-toggle menuFirst" id="button-logout" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" onclick="event.preventDefault();">
                                Sign Out
                            </a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </div><!-- /.container-fluid -->
    </nav>
</div>
</div>
<div class="header-container">
    <div class="container relative static-header">
        <a href="/"><img src="/images/ignatiuz-logo.png" class="logo-image margin-top-seven"></a>

        <div class="pull-right user-header">
            <img src="/images/log.png" class="user-image" width="65" height="65">
            <span class="user-detail">
                 <strong><?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['name']); ?><br> <?php echo e(\Illuminate\Support\Facades\Auth::user()['original']['employe']); ?></strong>
            </span>
        </div>
    </div>
</div>


<?php echo $__env->yieldContent('content'); ?>


<div class="navbar-inner navbar-inner-style my_input padding-left-twenty">
    <div class="footer-content">&copy Ignatiuz</div>
</div>
<!-- /container -->
<script src="/assets/jquery-1.9.1.min.js"></script>
<script src="/assets/scripts.js"></script>
<script type="text/javascript" src="/datatables/jQuery-2.2.3/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="/datatables/DataTables-1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/datatables/DataTables-1.10.12/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="/datatables/Buttons-1.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/datatables/Buttons-1.2.2/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="/datatables/Buttons-1.2.2/js/buttons.colVis.min.js"></script>
<script src="/bootstrap3/js/bootstrap.min.js"></script>
<script src="/js/jquery.jgrowl.js"></script>
<script src="/js/Calendar.js"></script>
<script src="/js/notify.js"></script>
<script type="text/javascript" src="/js/xlsx.core.min.js"></script>
<script type="text/javascript" src="/js/FileSaver.min.js"></script>
<script type="text/javascript" src="/js/tableExport.js"></script> 
<script src="/js/main.js"></script>
<script type="text/javascript" src="/js/jquery.timepicker.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

  <!--  <script src="/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="/assets/DT_bootstrap.js"></script>-->
<script>
    $(function() {

    });
</script>
</body>
</html>
