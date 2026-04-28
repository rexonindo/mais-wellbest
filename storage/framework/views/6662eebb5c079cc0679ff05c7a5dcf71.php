<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Menu</title>

    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f2f3f5;
            font-family: Arial, sans-serif;
        }

        .title {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .op-btn {
            display: block;
            width: 100%;
            padding: 25px;
            margin-bottom: 18px;
            text-align: center;
            background: #1a73e8;
            color: white;
            font-size: 1.4rem;
            font-weight: bold;
            border-radius: 18px;
            text-decoration: none;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.12);
        }

        .op-btn.red {
            background: #d93025;
        }

        .op-btn.green {
            background: #188038;
        }

        .op-btn.orange {
            background: #f57c00;
        }
    </style>
</head>
<body>

    <div class="title">Operator Menu</div>

    <a href="/production/log" class="op-btn">Production Log</a>
    <a href="/production/rework" class="op-btn orange">Rework Process</a>
    <a href="/production/stop" class="op-btn red">Stop Process</a>
    <a href="/logout" class="op-btn red">Logout</a>

</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views\operator\menu.blade.php ENDPATH**/ ?>