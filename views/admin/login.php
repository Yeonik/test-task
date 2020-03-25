<div class="card mt-4" style="max-width: 450px; margin: auto">
    <div class="card-body">
        <h4 class="card-title text-center">Вход в админку</h4>
        <?php if($alert['hasError']):?>
            <div class="alert alert-danger" role="alert">
                <strong><?= $alert['message']; ?></strong>
            </div>
        <?php endif;?>
        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="login" placeholder="Логин" autocomplete="off" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Пароль" autocomplete="off" required>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label for="remember" class="form-check-label"> запомнить</label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Отпавить</button>
            </div>
        </form>
    </div>
</div>