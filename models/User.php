<?php

class User
{
    public static function register($name, $email, $password)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO user (name, email, password) '
                . 'VALUES (:name, :email, :password)';

        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);

        return $result->execute();
    }
       
    /**
     * Проверяет имя: не меньше, чем 2 символа
     */

    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет имя: не меньше, чем 6 символов
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }


     public static function checkEmailExists($email)
    {

        $db = Db::getConnection();

        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email'; //подготовленный запрос, т.е запрос,куда мы прямо не передаем наш параметр, а используем специальный плейсхолдер

        $result = $db->prepare($sql);//В данном примере используется также метод prepare для подготовки запроса, перед тем, как присоединить данные пользователя. В таком случае инъекция SQL теоретически  невозможна, так как сами данные не вставляются в текст запроса SQL.
        $result->bindParam(':email', $email, PDO::PARAM_STR); //переводит в параметр метода в строковый
        $result->execute();

        if ($result->fetchColumn()) //проверяем есть ли записи
            return true;
        return false;
    }

     /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * @param string $email
     * @param string $password
     * @return mixed : ingeger user id or false
     */
    public static function checkUserData($email, $password)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();

        $user = $result->fetch();
        if ($user) {
            return $user['id'];
        }

        return false;
    }


/**
     * Редактирование данных пользователя
     * @param string $name
     * @param string $password
     */
    public static function edit($id, $name, $password)
    {
        $db = Db::getConnection();
        
        $sql = "UPDATE user 
            SET name = :name, password = :password 
            WHERE id = :id";
        
        $result = $db->prepare($sql);                                  
        $result->bindParam(':id', $id, PDO::PARAM_INT);       
        $result->bindParam(':name', $name, PDO::PARAM_STR);    
        $result->bindParam(':password', $password, PDO::PARAM_STR); 
        return $result->execute();
    }



    /**
     * Returns user by id
     * @param integer $id
     */
      public static function getUserById($id)
    {
        if ($id) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM user WHERE id = :id';

            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);

            // Указываем, что хотим получить данные в виде массива
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();


            return $result->fetch();
        }
    }

      public static function auth($userId)
    {
        
        $_SESSION['user'] = $userId;
    }


    public static function checkLogged()
    {
        
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        header("Location: /user/login");
    }

      public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }
}
