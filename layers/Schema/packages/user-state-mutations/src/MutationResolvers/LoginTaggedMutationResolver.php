<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractTaggedMutationResolver;

class LoginTaggedMutationResolver extends AbstractTaggedMutationResolver
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
            'website' => $this->getWebsiteLoginMutationResolver(),
        ];
    }
}
