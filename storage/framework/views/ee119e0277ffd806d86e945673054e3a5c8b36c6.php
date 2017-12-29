<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
		.message-body{
			font-family:Ariel,sans-serif;
			color: grey;
		}
    </style>
</head>

<body style="color:grey;">
	<div class="message-body">
		<h3>Dear <?php echo e($name); ?>, </h3>
		<p>Your Task  has been <?php echo e($status); ?> by Your Lead - <?php echo e($lead); ?> </p>
		<p>Task Title - <?php echo e($task_detail); ?></p>
		<p>Task Description : <span style="font-style:italic;font-weight:bold;"><?php echo e($description); ?></span></p>
		<?php if($comments): ?>
			<p>Comments : <?php echo e($comments); ?></p>
		<?php endif; ?>
		
		<div>
			<p>Thanks,<br>MyHub<br>Team Ignatiuz</p>
		</div>
	</div>

</body>
</html>
