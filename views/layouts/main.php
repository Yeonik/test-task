<?
/* @var $title string */
/* @var $content string */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap.min.css">
    <title><?= $title ?></title>
</head>
<body>
<div class="container">
    <?php if($user == null) :?>
        <div class="d-flex justify-content-end mt-2">
            <a href="/site" class="btn btn-outline-secondary mr-2">Главная</a>
            <a href="/admin" class="btn btn-outline-primary">Войти</a>
        </div>
    <?php else :?>
        <div class="d-flex justify-content-end mt-2">
            <a href="/site" class="btn btn-outline-secondary mr-2">Главная</a>
            <a href="/admin" class="btn btn-outline-secondary mr-2">Админ панель</a>
            <a href="/admin/logout" class="btn btn-outline-primary">Выйти</a>
        </div>
    <?php endif; ?>

    <?= $content ?>
</div>

    <script src="/assets/jquery/jquery.min.js"></script>
    <script src="/assets/bootstrap/bootstrap.min.js"></script>
</body>
</html>