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

<img src="http://<?= $_SERVER['SERVER_NAME'] ?>/images/ignatiuz-logo.png" width="250" height="102">
<div>
    <h3>Hello, <?php echo e($name); ?> </h3>
    <p>Your account is registered!</p>
    <p>Your login: <?php echo e($email); ?></p>
    <p>Your password: <?php echo e($password); ?></p>
    <p>Click here to <a href="http://<?= $_SERVER['SERVER_NAME'] ?>">Login</a></p><br><br>

    Regards,<br>Ignatiuz
</div>

</body>
</html>
