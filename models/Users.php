<?php

class Users extends mysqli
{	
	private static $instance;	// экземпляр класса
	private $msql;				// драйвер БД
	private $sid;				// id текущей сессии
	private $uid;				// id текущего пользователя
	

	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
			
		return self::$instance;
	}


	public function __construct()
	{
		$this->msql = Database::Instance();
		$this->sid = null;
		$this->uid = null;
	}

    /**
     * Очистка неиспользуемых сессий
     */
	public function clearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - 60 * 20); 			
		$t = "time_last < '%s'";
		$where = sprintf($t, $min);
		$this->msql->Delete('sessions', $where);
	}

    /** Авторизация
     * @param $login     - логин
     * @param $password  - пароль
     * @param bool $remember - нужно ли запомнить в куках
     * @return bool
     */
	public function login($login, $password, $remember = true)
	{
		// вытаскиваем пользователя из БД 
		$user = $this->getUserByLogin($login);

		if ($user == null)
			return false;
		
		$id_user = $user['id_user'];
				
		// проверяем пароль
		if ($user['password'] != md5($password)){
            return false;
        }
				
		// запоминаем имя и md5(пароль)
		if ($remember)
		{
			$expire = time() + 3600 * 24 * 90;
			setcookie('login', $login, $expire);
			setcookie('password', md5($password), $expire);
		}

		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($id_user);

		return true;
	}

    /**
     * Выход
     */
	public function logout()
	{
		setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);		
		$this->sid = null;
		$this->uid = null;
	}

    /**
     * Получение пользователя
     * @param null $id_user - если не указан, брать текущего
     * @return null | array данные пользователя
     */
	public function getUser($id_user = null)
	{	
		// Если $id_user не указан, берем его по текущей сессии.
		if ($id_user == null)
			$id_user = $this->getUserId();
			
		if ($id_user == null)
			return null;
			
		// А теперь просто возвращаем пользователя по id.
		$t = "SELECT * FROM users WHERE id_user = '%d'";
		$query = sprintf($t, $id_user);
		$result = $this->msql->Select($query);
		return $result[0];		
	}

    /**
     * Получает пользователя по логину
     * @param $login
     * @return mixed
     */
	public function getUserByLogin($login)
	{	
		$t = "SELECT * FROM users WHERE login = '%s'";
		$query = sprintf($t, $login );
		$result = $this->msql->Select($query);
		return $result[0];
	}


    /**
     * Получение id_user текущего пользователя
     * @return null | int
     */
	public function getUserId()
	{	
		// Проверка кеша.
		if ($this->uid != null)
			return $this->uid;	

		// Берем по текущей сессии.
		$sid = $this->getSid();
				
		if ($sid == null)
			return null;
			
		$t = "SELECT id_user FROM sessions WHERE sid = '%s'";
		$query = sprintf($t, $sid);
		$result = $this->msql->Select($query);
				
		// Если сессию не нашли - значит пользователь не авторизован.
		if (count($result) == 0)
			return null;
			
		// Если нашли - запоминм ее.
		$this->uid = $result[0]['id_user'];

		return $this->uid;
	}

    /**
     * Функция возвращает идентификатор текущей сессии
     * @return null|string
     */
	private function getSid()
	{
		// Проверка кеша.
		if ($this->sid != null)
			return $this->sid;
	
		// Ищем SID в сессии.
		$sid = $_SESSION['sid'];
								
		// Если нашли, попробуем обновить time_last в базе. 
		// Заодно и проверим, есть ли сессия там.
		if ($sid != null)
		{
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			$t = "sid = '%s'";
			$where = sprintf($t, $sid);
			$affected_rows = $this->msql->Update('sessions', $session, $where);

			if ($affected_rows == 0)
			{
				$t = "SELECT count(*) FROM sessions WHERE sid = '%s'";		
				$query = sprintf($t, $sid);
				$result = $this->msql->Select($query);
				
				if ($result[0]['count(*)'] == 0)
					$sid = null;			
			}			
		}		
		
		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключиться.
		if ($sid == null && isset($_COOKIE['login']))
		{
			$user = $this->getUserByLogin($_COOKIE['login']);
			
			if ($user != null && $user['password'] == $_COOKIE['password'])
				$sid = $this->OpenSession($user['id_user']);
		}
		
		// Запоминаем в кеш.
		if ($sid != null)
			$this->sid = $sid;
		
		// Возвращаем, наконец, SID.
		return $sid;		
	}

    /**
     * Открытие новой сессии
     * @param $id_user
     * @return string
     */
	private function openSession($id_user)
	{
		// генерируем SID
		$sid = $this->generateStr(10);
				
		// вставляем SID в БД
		$now = date('Y-m-d H:i:s'); 
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;				
		$this->msql->Insert('sessions', $session); 
				
		// регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;				
				
		// возвращаем SID
		return $sid;	
	}

    /**
     * Генерация случайной последовательности
     * @param int $length
     * @return string
     */
	private function generateStr($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  

		while (strlen($code) < $length) 
            $code .= $chars[mt_rand(0, $clen)];  

		return $code;
	}
}
