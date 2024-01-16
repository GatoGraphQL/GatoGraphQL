<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver;

class RootMyCommentsFilterInputObjectTypeResolver extends AbstractCommentsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyCommentsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s comments', 'media-mutations');
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
