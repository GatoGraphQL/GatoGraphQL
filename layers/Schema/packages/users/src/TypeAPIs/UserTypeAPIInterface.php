<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type User
     */
    public function isInstanceOfUserType(object $object): bool;

    public function getUserById($value);
    public function getUserByEmail($value);
    public function getUsers($query = array(), array $options = []): array;
    public function getUserCount(array $query = [], array $options = []): int;
    public function getUserDisplayName($user_id);
    public function getUserEmail($user_id);
    public function getUserFirstname($user_id);
    public function getUserLastname($user_id);
    public function getUserLogin($user_id);
    public function getUserDescription($user_id);
    public function getUserURL($user_id);
}
