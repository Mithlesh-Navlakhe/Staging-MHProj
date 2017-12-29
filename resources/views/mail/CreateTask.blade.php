<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
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
		<h3>Dear {{ $name }}, </h3>
		<p>You have been assinged a task by Lead - {{ $lead }} </p>
		<p>Task Title - <span style="font-weight:bold;">{{ $task_detail }}</span></p>
		<div>
			<p>Thanks,<br>MyHub<br>Team Ignatiuz</p>
		</div>
	</div>

</body>
</html>
