<?php
namespace PoPSchema\Users;

interface FunctionAPI
{
    public function getAuthorBase();
    public function getUserById($value);
    public function getUserByEmail($value);
    public function getUserBySlug($value);
    public function getUserByLogin($value);
    public function getUsers($query = array(), array $options = []): array;
    public function getUserCount(array $query = [], array $options = []): int;
    public function getUserDisplayName($user_id);
    public function getUserEmail($user_id);
    public function getUserFirstname($user_id);
    public function getUserLastname($user_id);
    public function getUserLogin($user_id);
    public function getUserDescription($user_id);
    public function getUserRegistrationDate($user_id);
    public function getUserURL($user_id);
}
