<?php
/**
 * Created by PhpStorm.
 * User: Rn
 * Date: 24.03.2020
 * Time: 18:56
 */

class BaseController
{
    protected $view;

    protected function Render($fileName, $vars = array())
    {
        $users = Users::Instance();
        $user = $users->getUser();
        $title = ($vars['title']) ? $vars['title'] : '';
        $content = $this->Template($fileName, $vars);

        $this->view = 'layouts';

        return $this->Template('main.php', [
            'title' => $title,
            'content' => $content,
            'user' => $user,
        ]);
    }

    protected function Template($fileName, $vars = array())
    {
        $fileName = ROOT.'/views/'.$this->view.'/'.$fileName;
        if(file_exists($fileName)){
            extract($vars);
            ob_start();
            include $fileName;
            return ob_get_clean();
        }
        else{
            return "Файл шаблона {$fileName} не найден!";
        }
    }


    protected function checkData($data) {

        foreach ($data as $key => $value){
            $data[$key] = strip_tags($data[$key]);
            $data[$key] = htmlspecialchars($data[$key], ENT_QUOTES);
        }

        return $data;
    }

    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}