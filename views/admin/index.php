<h1 class="text-uppercase text-center pb-4">Админ панель</h1>
<nav aria-label="Page navigation example">
    <ul class="pagination d-flex justify-content-center">

        <?php if($current_page != 1 ) : ?>
            <li class="page-item">
                <a class="page-link" href="/admin/index/?page=<?= $current_page - 1?>" aria-label="Previous">
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
                <li class="page-item"><a class="page-link" href="/admin/index/?page=<?= $page.$sort_link ?>"><?=$page?></a></li>
            <? endif ?>
        <? endwhile ?>

        <?php if($current_page < $total_pages) : ?>
            <li class="page-item">
                <a class="page-link" href="/admin/index/?page=<?= $current_page + 1?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Следующая</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<div class="list-group">
    <?php foreach( $tasks as $task):?>
        <div class="list-group-item">
            <p class="mb-1"><strong>Имя пользователя:</strong> <?= $task['userName']?> </p>
            <p class="mb-1"><strong>Email:</strong> <?= $task['email']?> </p>
            <p class="mb-1"><strong>Статус:</strong>
                <?= ($task['status']) ? 'Выполнено' : 'Не выполнено'?>
                <?= ($task['modify']) ? ', отредактировано администратором' : ''?>
            </p>

            <p class="mb-1"><strong>Текст задачи:</strong> <?= $task['text']?></p>

            <a href="/admin/update/<?= $task['id'] ?>">Редактировать</a>
            <a href="/admin/delete/<?= $task['id'] ?>" onclick="return (confirm('Удалить?'));">Удалить</a>
        </div>
    <?php endforeach; ?>
</div>