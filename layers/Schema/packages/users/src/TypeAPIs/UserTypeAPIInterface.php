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

    public function getUserById(string | int $userID): ?object;
    public function getUserByEmail(string $email): ?object;
    public function getUserByLogin(string $login): ?object;
    public function getUsers($query = array(), array $options = []): array;
    public function getUserCount(array $query = [], array $options = []): int;
    public function getUserDisplayName(string | int | object $userObjectOrID): ?string;
    public function getUserEmail(string | int | object $userObjectOrID): ?string;
    public function getUserFirstname(string | int | object $userObjectOrID): ?string;
    public function getUserLastname(string | int | object $userObjectOrID): ?string;
    public function getUserLogin(string | int | object $userObjectOrID): ?string;
    public function getUserDescription(string | int | object $userObjectOrID): ?string;
    public function getUserURL(string | int | object $userObjectOrID): ?string;
    public function getUserWebsiteUrl(string | int | object $userObjectOrID): ?string;
    public function getUserSlug(string | int | object $userObjectOrID): ?string;
    public function getUserId(object $user): string | int;
}
