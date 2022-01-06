<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

class CommentResponsesFilterInputObjectTypeResolver extends AbstractCommentsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentResponsesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter comment responses', 'comments');
    }

    protected function addParentInputFields(): bool
    {
        return false;
    }

    protected function addCustomPostInputFields(): bool
    {
        return false;
    }
}
