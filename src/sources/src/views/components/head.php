<?php

function getTitle()
{
    $suffix = $_SESSION['user'] ? ' - ' . ucfirst($_SESSION['user']['username']) : '';
    $title = $_ENV['APP_NAME'] . $suffix;
    return $title;
}

?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= getTitle() ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
    html,body {
        margin: 0;
        padding: 0;
        height: 100vh;
    }

    body, main {
        min-height: 100vh !important;
    }
</style>