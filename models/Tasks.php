<?php

class Tasks
{

    public $per_page = 8;

    /**
     * @param $params['page'] - текущая страница
     * @param $params['sort'] - поле по которому нужно сортировать
     * @param $params['asc'] - напровление сортировки
     *
     * @return array,
     *          tasks - массив с тасками
     *          total_pages - общее колисество страниц
     */
    public function getTasks($params)
    {
        $db = new Database();

        $start = ($params['page'] - 1) * $this->per_page;

        $asc = ($params['asc']) ? 'ASC' : 'DESC';
        $orderBy = ($params['sort']) ? 'ORDER BY '.$params['sort'].' '.$asc : '';

        $tasks = $db->Select('SELECT * FROM tasks '.$orderBy.' LIMIT '.$start.','.$this->per_page);

        // общее количество страниц для пагинации
        $count = $db->Select('SELECT COUNT(*) as count FROM tasks');
        $total_pages = $count[0]['count'] / $this->per_page;

        return ['tasks' => $tasks, 'total_pages' => $total_pages];
    }

    public function setTask($post)
    {
        $db = new Database();
        return $db->Insert('tasks', $post);
    }

    public function getOneTask($id)
    {
        $db = new Database();
        $task = $db->Select('SELECT * FROM tasks WHERE id='.$id);
        return $task[0];
    }

    public function updateTask($id, $post)
    {
        $db = new Database();
        return $db->Update('tasks', $post,'id='.$id );
    }
    public function deleteTask($id)
    {
        $db = new Database();
        return $db->Delete('tasks', 'id='.$id );
    }

}