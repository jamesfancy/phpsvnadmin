<?php

$filename = 'db.sqlite';
$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $filename;
$pdo = new PDO("sqlite:$filename");

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dao.interface.php';

class SqliteDao implements IDao {

    private function execute($sql, $params) {
        global $pdo;
        $cmd = $pdo->prepare($sql);
        if (!$cmd->execute($params)) {
            $error = $cmd->errorInfo();
            throw new Exception($error[2], $error[1]);
        }

        $cmd->setFetchMode(PDO::FETCH_ASSOC);
        return $cmd;
    }

    public function changePassword($username, $salt, $password) {
        $sql = <<<EOT
update user
   set salt = :salt,
       password = :password
 where username = :username
EOT;

        $cmd = $this->execute($sql, [
            ':username' => $username,
            ':salt' => $salt,
            ':password' => $password
        ]);

        return $cmd->rowCount();
    }

    public function updateUser($user) {
        $sql = <<<EOT
update user
   set realname = :realname,
       type = :type,
 where username = :username
EOT;
        return $this->execute($sql, [
                    ':realname' => $user->realname,
                    ':type' => $user->type,
                    ':username' => $user->username
                ])->rowCount();
    }

    public function deleteUser($username) {
        $sql = <<<EOT
delete from user
 where username = :username
EOT;

        return $this->execute($sql, [
                    ':username' => $username
                ])->rowCount();
    }

    public function createUser($user) {
        $sql = <<<EOT
insert into user
       (username, password, salt, realname, type, email)
values (:user, :pass, :salt, :realname, :type, :email)
EOT;

        return $this->execute($sql, [
                    ':user' => $user->username,
                    ':pass' => $user->password,
                    ':salt' => $user->salt,
                    ':realname' => isset($user->realname) ? $user->realname : '',
                    ':type' => isset($user->type) ? $user->type : 0,
                    ':email' => isset($user->email) ? $user->email : ''
                ])->rowCount();
    }

    private function getUserInternal($fields, $username) {
        $sql = <<<EOT
select $fields
  from user
 where username = :username
EOT;

        $cmd = $this->execute($sql, [':username' => $username]);
        $rows = $cmd->fetchAll();
        return empty($rows) ? null : $rows[0];
    }

    public function getUserWithPassword($username) {
        return $this->getUserInternal(
                        'username, password, salt, realname, type, email, verified', $username);
    }

    public function getUser($username) {
        return $this->getUserInternal(
                        'username, realname, type, email, verified', $username);
    }

    public function getUserList($page) {
        global $config;
        $count = $config->options['rowsInPage'];
        if ($count < 1) {
            $count = 15;
        }

        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $count;

        $sql = <<<EOT
select username, realname, email, verified
  from user
 limit $count offset $offset
EOT;

        return $this->execute($sql, null)->fetchAll();
    }

}

$dao = new SqliteDao();
