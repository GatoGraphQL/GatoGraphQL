<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractOneofMutationResolver;

class LoginUserOneofMutationResolver extends AbstractOneofMutationResolver
{
    private ?LoginUserByCredentialsMutationResolver $loginUserByCredentialsMutationResolver = null;

    final public function setLoginUserByCredentialsMutationResolver(LoginUserByCredentialsMutationResolver $loginUserByCredentialsMutationResolver): void
    {
        $this->loginUserByCredentialsMutationResolver = $loginUserByCredentialsMutationResolver;
    }
    final protected function getLoginUserByCredentialsMutationResolver(): LoginUserByCredentialsMutationResolver
    {
        return $this->loginUserByCredentialsMutationResolver ??= $this->instanceManager->getInstance(LoginUserByCredentialsMutationResolver::class);
    }

    protected function getInputFieldNameMutationResolvers(): array
    {
        return [
            'credentials' => $this->getLoginUserByCredentialsMutationResolver(),
        ];
    }
}
