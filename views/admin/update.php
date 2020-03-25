
<div class="card mt-4" style="max-width: 650px; margin: auto">
    <div class="card-body">
        <h4 class="card-title text-center">Редактирование задачи</h4>
        <?php if($alert['hasError']):?>
            <div class="alert alert-danger" role="alert">
                <strong><?= $alert['message']; ?></strong>
            </div>
        <?php endif;?>
        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="userName" value="<?= $task['userName']?>" placeholder="Введите ваше имя" autocomplete="off" disabled>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" value="<?= $task['email'] ?>" placeholder="Email" autocomplete="off" disabled>
            </div>
            <div class="form-group">
                <textarea type="text" class="form-control" rows="5" name="text" autocomplete="off" required><?= $task['text'] ?></textarea>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?= ($task['status']) ? 'checked' : ''?>>
                    <label for="status" class="form-check-label"> выполнено</label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Отпавить</button>
            </div>
        </form>
    </div>
</div>