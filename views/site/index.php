<?php
/* @var $tasks array */
/* @var $total_pages string */
/* @var $current_page integer */
/* @var $error array */
/* @var $asc array */
?>

<h1 class="text-uppercase text-center pb-4">Задачник</h1>

<nav aria-label="Page navigation example">
    <ul class="pagination d-flex justify-content-center">

        <?php if($current_page != 1 ) : ?>
        <li class="page-item">
            <a class="page-link" href="/site/index/?page=<?= $current_page - 1?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Предыдущая</span>
            </a>
        </li>
        <?php endif; ?>

        <?php $page = 0;
        while ($page++ < $total_pages): ?>
            <? if ($page == $current_page): ?>
                <li class="page-item active">
                    <span class="page-link" ><?=$page?> <span class="sr-only">(current)</span></span>
                </li>
            <? else: ?>
                <li class="page-item"><a class="page-link" href="/site/index/?page=<?= $page.$sort_link ?>"><?=$page?></a></li>
            <? endif ?>
        <? endwhile ?>

        <?php if($current_page < $total_pages) : ?>
        <li class="page-item">
            <a class="page-link" href="/site/index/?page=<?= $current_page + 1?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Следующая</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>

<nav class="nav">
    <span class="nav-link">Сортировать по</span>
    <a class="nav-link" href="/site/index/?sort=userName<?= $asc['userName'] ?>">Имени</a>
    <a class="nav-link" href="/site/index/?sort=email<?= $asc['email'] ?>">Email</a>
    <a class="nav-link" href="/site/index/?sort=status<?= $asc['status'] ?>">Статусу</a>
</nav>

<div class="list-group">
<?php foreach( $tasks as $task):?>
    <div class="list-group-item">
        <p class="mb-1"><strong>Имя пользователя:</strong> <?= $task['userName']?> </p>
        <p class="mb-1"><strong>Email:</strong> <?= $task['email']?> </p>
        <p class="mb-1"><strong>Статус:</strong>
            <?= ($task['status']) ? 'Выполнено' : 'Не выполнено'?>
        </p>

        <p class="mb-1"><strong>Текст задачи:</strong> <?= $task['text']?></p>
    </div>
<?php endforeach; ?>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h4 class="card-title text-center">Добавить задачу</h4>

        <?php if($alert['hasError']):?>
            <div class="alert alert-danger" role="alert">
                <strong><?= $alert['message']; ?></strong>
            </div>
        <?php endif;?>

        <?php if($alert['hasSuccess']):?>
            <div class="alert alert-success" role="alert">
                <strong><?= $alert['message']; ?></strong>
            </div>
        <?php endif;?>

        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="userName" value="<?= $_POST['userName']?>" placeholder="Введите ваше имя" autocomplete="off" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" value="<?= $_POST['email']?>" placeholder="Email" autocomplete="off" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="text" placeholder="Введите текст задачи" required autocomplete="off"><?= $_POST['text']?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Отпавить</button>
            </div>
        </form>
    </div>
</div>


