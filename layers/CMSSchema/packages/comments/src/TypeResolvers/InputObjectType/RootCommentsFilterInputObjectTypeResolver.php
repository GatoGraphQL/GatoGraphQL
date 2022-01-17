<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

class RootCommentsFilterInputObjectTypeResolver extends AbstractCommentsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCommentsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter comments', 'comments');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}
