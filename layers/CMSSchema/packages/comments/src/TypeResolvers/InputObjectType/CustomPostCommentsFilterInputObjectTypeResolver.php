<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

class CustomPostCommentsFilterInputObjectTypeResolver extends AbstractCommentsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostCommentsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter comments from custom posts', 'comments');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function addCustomPostInputFields(): bool
    {
        return false;
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            // By default fetch top-level comments
            'parentID' => 0,
            default => parent::getInputFieldDefaultValue($inputFieldName)
        };
    }
}
