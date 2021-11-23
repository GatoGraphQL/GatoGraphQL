<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractOneofMutationResolver;

class LoginUserOneofMutationResolver extends AbstractOneofMutationResolver
{
    private ?WebsiteLoginMutationResolver $websiteLoginMutationResolver = null;

    final public function setWebsiteLoginMutationResolver(WebsiteLoginMutationResolver $websiteLoginMutationResolver): void
    {
        $this->websiteLoginMutationResolver = $websiteLoginMutationResolver;
    }
    final protected function getWebsiteLoginMutationResolver(): WebsiteLoginMutationResolver
    {
        return $this->websiteLoginMutationResolver ??= $this->instanceManager->getInstance(WebsiteLoginMutationResolver::class);
    }

    protected function getInputFieldNameMutationResolvers(): array
    {
        return [
            'credentials' => $this->getWebsiteLoginMutationResolver(),
        ];
    }
}
