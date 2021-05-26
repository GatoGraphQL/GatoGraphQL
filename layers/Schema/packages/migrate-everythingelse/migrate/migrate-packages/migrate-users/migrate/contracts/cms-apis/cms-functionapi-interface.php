<?php
namespace PoPSchema\Users;

interface FunctionAPI
{
    public function getAuthorBase();
    public function getUserBySlug($value);
    public function getUserByLogin($value);
    public function getUserRegistrationDate($user_id);
}
