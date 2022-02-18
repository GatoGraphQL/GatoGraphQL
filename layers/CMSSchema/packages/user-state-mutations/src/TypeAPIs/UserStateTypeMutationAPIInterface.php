<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeAPIs;

use PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserStateTypeMutationAPIInterface
{
    /**
     * @throws UserStateMutationException In case of error
     */
    public function login(array $credentials): object;
    public function logout(): void;
}
