<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-Documentequiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel & MUSQL DB Connection</title>
</head>
<body>
    <div>
        <?php
            if(DB::connection()->getPdo()){
                echo "Successfully Connected to DB and DB name is ".DB::connection()->getDatabaseName();
            }
        ?>
    </div>
</body>
</html>
