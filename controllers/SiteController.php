<?php

class SiteController extends BaseController
{
    protected $view = 'site';

    public function actionIndex() {

        $tasksModel = new Tasks();

        $params = $this->checkData($_GET);

        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['sort'] = ($params['sort']) ? $params['sort'] : false;
        $params['asc']  = ($params['asc'])  ? $params['asc']  : false;

        $alert = ['hasError' => false, 'hasSuccess' => false, 'message' => ''];

        if($this->IsPost()){
            $post = $this->checkData($_POST);

            if(!$this->required($post)){
                $alert['hasError'] = true;
                $alert['message'] = 'Вы заполнели не все поля!';
            }
            if(!$this->isValidEmail($post['email'])){
                $alert['hasError'] = true;
                $alert['message'] .= ' Не верно указан email!';
            }

            if($alert['hasError'] === false){
                if($tasksModel->setTask($post)){
                    $alert['hasSuccess'] = true;
                    $alert['message'] = 'Задача успешно добавлена!';
                    unset($_POST);
                } else {
                    $alert['hasError'] = true;
                    $alert['message'] = 'Нe удалось добавить задачу!';
                }
            }
        }
        $tasksModel->per_page = 3;
        $data = $tasksModel->getTasks($params);

        $sort_link = ($params['sort']) ? '&sort='.$params['sort'].'&asc='.$params['asc'] : '';
        $asc = ['userName' => '', 'email' => '','status' => ''];
        foreach ($asc as $key => $value){
            $asc[$key] = ($params['sort'] == $key) ? '&asc='.(!$params['asc']) : '';
        }

        echo $this->Render('index.php', [
            'title' => 'Задачник - стартовая страница',
            'tasks' => $data['tasks'],
            'total_pages' => $data['total_pages'],
            'current_page' => $params['page'],
            'alert' => $alert,
            'asc' => $asc,
            'sort_link' => $sort_link,
        ]);
    }


    private function required($post) {
        if(empty($post['userName']) || empty($post['email']) || empty($post['text'])){
            return false;
        }
        return true;
    }

    private function isValidEmail($email){
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email);
    }
}