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
		<p>Hi, </p>
		<p>My Today’s Status Report  are as follows:- </p>
		<p>Today’s Tasks:- </p>
		<p><span><?php echo $todayTask; ?></span></p>
		<div>
			<p>Thanks,<br><?php echo e($name); ?></p>
		</div>
		<p>Please Note: This is auto generated email, Please do not respond to this email.</p>
	</div>

</body>
</html>
