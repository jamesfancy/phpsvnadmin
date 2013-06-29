<?php
interface IDao {
    public function getUserList($page);
    public function getUser($username);
    public function getUserWithPassword($username);
    public function createUser($user);
    public function deleteUser($username);
    public function updateUser($user);
    public function changePassword($username, $salt, $password);
}
