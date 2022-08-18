<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractOneofMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

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
