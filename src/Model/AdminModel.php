<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class AdminModel
{
	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    public function getAllUsers()
    {
      $sql = 'SELECT user_id, username, email, display_name FROM user';
      $result = $this->_db->fetchAll($sql);
      return $result;
    }

    public function viewUser($user_id)
    {
        $user = $this->getUserById($user_id);

        return $user;
    }

    public function deleteUser($user_id)
    {
  
        $sql = 'DELETE FROM user WHERE user_id = ?';

        $this->_db->executeQuery($sql, array((int) $user_id));       
    }

    public function getUserById($user_id)
    {
        $sql = 'SELECT user_id, username, email, display_name FROM user WHERE user_id = ? LIMIT 1';

        return $this->_db->fetchAll($sql, array((int) $user_id));
    }

    public function loginAdmin($data)
    {

        $admin = $this->getAdminByPassword($data['password']);

        if (count($admin)) {
            if ($admin['password'] == crypt($data['password'], $admin['password'])) {
                return $admin;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getAdminByPassword($password)
    {
        $crypted_password = crypt($password);
        $sql = 'SELECT * FROM admin WHERE password = ?';
        return $this->_db->fetchAssoc($sql, array((string) $crypted_password));
    }

    public function logoutAdmin()
    {
        if (($admin = $app['session']->get('admin')) !== null) {
            $app['session']->remove('admin');
        }
        $app['session']->getFlashBag()->add('success', array('title' => 'Ok', 'content' => 'You have been logout successfully.'));
        return $app['twig']->render('logout.twig');
    }

    public function countUsersPages($limit)
    {
        $pagesCount = 0;
        $sql = 'SELECT COUNT(*) as pages_count FROM user';
        $result = $this->_db->fetchAssoc($sql);
        if ($result) {
            $pagesCount =  ceil($result['pages_count']/$limit);
        }
        return $pagesCount;
    }

    public function getUsersPage($page, $limit, $pagesCount)
    {
        if (($page <= 1) || ($page > $pagesCount)) {
            $page = 1;
        }
        $sql = 'SELECT user_id, username, email, display_name FROM user LIMIT :start, :limit';
        $statement = $this->_db->prepare($sql);
        $statement->bindValue('start', ($page-1)*$limit, \PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}