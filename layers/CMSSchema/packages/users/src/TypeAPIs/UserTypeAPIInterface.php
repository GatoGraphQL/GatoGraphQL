<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type User
     */
    public function isInstanceOfUserType(object $object): bool;

    public function getUserByID(string | int $userID): ?object;
    public function getUserByEmail(string $email): ?object;
    public function getUserByLogin(string $login): ?object;
    public function getUsers(array $query = [], array $options = []): array;
    public function getUserCount(array $query = [], array $options = []): int;
    public function getUserDisplayName(string | int | object $userObjectOrID): ?string;
    public function getUserEmail(string | int | object $userObjectOrID): ?string;
    public function getUserFirstname(string | int | object $userObjectOrID): ?string;
    public function getUserLastname(string | int | object $userObjectOrID): ?string;
    public function getUserLogin(string | int | object $userObjectOrID): ?string;
    public function getUserDescription(string | int | object $userObjectOrID): ?string;
    public function getUserURL(string | int | object $userObjectOrID): ?string;
    public function getUserURLPath(string | int | object $userObjectOrID): ?string;
    public function getUserWebsiteURL(string | int | object $userObjectOrID): ?string;
    public function getUserSlug(string | int | object $userObjectOrID): ?string;
    public function getUserID(object $user): string | int;
}
