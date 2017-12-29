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
		
		<div>
			@if($emailTask)
				<span>{!! $emailTask !!}</span>
			@endif
		</div>
		<div>
			<p>Thanks,<br>{{ $name }},<br>Ignatiuz Software Pvt. Ltd.</p>
		</div>
	</div>

</body>
</html>
