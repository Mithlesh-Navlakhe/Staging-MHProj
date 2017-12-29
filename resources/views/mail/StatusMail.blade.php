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
		<p>Your Task  has been {{ $status }} by Your Lead - {{ $lead }} </p>
		<p>Task Title - {{ $task_detail }}</p>
		<p>Task Description : <span style="font-style:italic;font-weight:bold;">{{ $description }}</span></p>
		@if($comments)
			<p>Comments : {{ $comments }}</p>
		@endif
		
		<div>
			<p>Thanks,<br>MyHub<br>Team Ignatiuz</p>
		</div>
	</div>

</body>
</html>
