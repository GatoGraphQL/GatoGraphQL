<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;

class RootGenericCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootGenericCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter custom posts', 'customposts');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}
