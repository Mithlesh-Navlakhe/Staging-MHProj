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
    </style>
</head>

<body style="color:grey">

<img src="http://<?= $_SERVER['SERVER_NAME'] ?>:<?= $_SERVER['SERVER_PORT'] ?>/images/ignatiuz-logo.png" width="250" height="102">
<div>
    <h3>Hello <?php echo e($name); ?> </h3>
    <p>You have been invited to collaborate with your team members on myHub. Please find the login details below</p>
    <p>   Login: <?php echo e($email); ?><br>Password: <?php echo e($password); ?> <br><br>
        Visit myHub portal at  <a href="http://<?= $_SERVER['SERVER_NAME'] ?>:<?= $_SERVER['SERVER_PORT'] ?>">http://<?= $_SERVER['SERVER_NAME'] ?></a> to get started.<br><br>
    </p>


    Cheers!<br>Team Ignatiuz
</div>

</body>
</html>
