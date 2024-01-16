<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver;

class RootMyMediaFilterInputObjectTypeResolver extends AbstractCommentsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyMediaFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s media items', 'media-mutations');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }

    protected function treatCommentStatusAsSensitiveData(): bool
    {
        return false;
    }
}
