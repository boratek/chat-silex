<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class UserModel
{
	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    public function getUser($display_name)
    {
      $sql = "SELECT user_id, username, email, display_name FROM user WHERE display_name = '$display_name' LIMIT 1";
      $result = $this->_db->fetchAll($sql);
      return $result;
    }

    public function registerUser($data)
    {
    	$sql = "INSERT INTO user (user_id, username, email, display_name, password) VALUES (0, ?, ?, ?, ENCRYPT(?))";

    	$result = $this->_db->executeQuery($sql, array($data['name'], $data['email'], $data['display_name'], $data['password']));
    }

    public function loginUser($data)
    {
        $user = $this->getUserByLogin($data['display_name']);

        if (count($user)) {
            if ($user['password'] == crypt($data['password'], $user['password'])) {
                return $user;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getUserByLogin($display_name)
    {
        $sql = 'SELECT * FROM user WHERE display_name = ?';
        return $this->_db->fetchAssoc($sql, array((string) $display_name));
    }
}