<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractTaggedMutationResolver;
use stdClass;

class LoginMutationResolver extends AbstractTaggedMutationResolver
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

    protected function getMutationResolvers(): array
    {
        return [
            'website' => $this->getWebsiteLoginMutationResolver(),
        ];
    }

    protected function getTaggedInputObjectFormData(array $formData): stdClass
    {
        return $formData[MutationInputProperties::CREDENTIALS];
    }
}
