<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserStateTypeAPIInterface
{
    /**
     * @return mixed Result or Error
     */
    public function login(array $credentials);
    public function logout(): void;
}
