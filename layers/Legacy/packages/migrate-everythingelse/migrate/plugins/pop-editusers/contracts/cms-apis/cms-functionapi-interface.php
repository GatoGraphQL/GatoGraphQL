<?php
namespace PoP\EditUsers;

interface FunctionAPI
{
    public function insertUser($user_data);
    public function updateUser($user_data);
}
