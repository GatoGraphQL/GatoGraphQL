<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

class RootCreateMediaItemInputObjectTypeResolver extends AbstractCreateMediaItemInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateMediaItemInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to add a comment to a custom post', 'media-mutations');
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }

    protected function addParentCommentInputField(): bool
    {
        return true;
    }

    protected function isParentCommentInputFieldMandatory(): bool
    {
        return false;
    }
}
