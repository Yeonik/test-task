<?php

class AdminController extends BaseController
{
    protected $view = 'admin';

    public function actionIndex() {
        $users = Users::Instance();
        if($users->getUser() == null){
            header('Location: /admin/login');
        }

        $tasksModel = new Tasks();
        $params = $this->checkData($_GET);

        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['sort'] = ($params['sort']) ? $params['sort'] : false;
        $params['asc']  = ($params['asc'])  ? $params['asc']  : false;

        $data = $tasksModel->getTasks($params);

        echo $this->Render('index.php',[
            'title' => 'Админ панель',
            'tasks' => $data['tasks'],
            'total_pages' => $data['total_pages'],
            'current_page' => $params['page'],
        ]);
    }


    public function actionLogin() {
        session_start();
        $users = Users::Instance();
        if($users->getUser() != null){
            header('Location: /admin/index');
        }

        $alert = ['hasError' => false, 'hasSuccess' => false, 'message' => ''];

        if ($this->IsPost() && !empty($_POST)) {
            if ($users->login($_POST['login'], $_POST['password'], isset($_POST['remember']))) {
                header('Location: /admin/index');
                die();
            } else {
                $alert['hasError'] = true;
                $alert['message'] = 'Не верно указан логин или пароль!';
            }
        }

        echo $this->Render('login.php',[
            'title' => 'Авторизация',
            'alert' => $alert,
        ]);
    }

    public function actionLogout() {
        $users = Users::Instance();
        $users->Logout();

        header('Location: /admin/login');
    }

    public function actionUpdate($id) {
        $users = Users::Instance();
        if($users->getUser() == null){
            header('Location: /admin/login');
            die();
        }

        $id = (int) $id;
        $tasksModel = new Tasks();
        $task = $tasksModel->getOneTask($id);

        $alert = ['hasError' => false, 'hasSuccess' => false, 'message' => ''];

        if($this->IsPost()){
            $post = $this->checkData($_POST);
            $post['status'] = ($post['status']) ? $post['status'] : 0;
            if ($task['text'] != $post['text']) {
                $post['modify'] = 1;
            }

            if($tasksModel->updateTask($id, $post)){
                $alert['hasSuccess'] = true;
                $alert['message'] = 'Задача успешно обновлена!';

                header('Location: /admin/index');
            } else {
                $alert['hasError'] = true;
                $alert['message'] = 'Нe удалось обновить задачу!';
            }

        }

        echo $this->Render('update.php',[
            'title' => 'Редактирование задачи',
            'alert' => $alert,
            'task' => $task,
        ]);
    }

    public function actionDelete($id) {
        $users = Users::Instance();
        if($users->getUser() == null){
            header('Location: /admin/login');
            die();
        }
        $tasksModel = new Tasks();
        $tasksModel->deleteTask($id);
        header('Location: /admin/index');
    }
}