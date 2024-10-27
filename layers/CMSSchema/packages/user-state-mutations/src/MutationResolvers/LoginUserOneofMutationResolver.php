<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractOneofMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class LoginUserOneofMutationResolver extends AbstractOneofMutationResolver
{
    private ?LoginUserByCredentialsMutationResolver $loginUserByCredentialsMutationResolver = null;

    final protected function getLoginUserByCredentialsMutationResolver(): LoginUserByCredentialsMutationResolver
    {
        if ($this->loginUserByCredentialsMutationResolver === null) {
            /** @var LoginUserByCredentialsMutationResolver */
            $loginUserByCredentialsMutationResolver = $this->instanceManager->getInstance(LoginUserByCredentialsMutationResolver::class);
            $this->loginUserByCredentialsMutationResolver = $loginUserByCredentialsMutationResolver;
        }
        return $this->loginUserByCredentialsMutationResolver;
    }

    /**
     * @return array<string,MutationResolverInterface>
     */
    protected function getInputFieldNameMutationResolvers(): array
    {
        return [
            'credentials' => $this->getLoginUserByCredentialsMutationResolver(),
        ];
    }
}
