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
        return $this->getTranslationAPI()->__('Input to filter comments', 'comments');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }

    // public function getInputFieldDefaultValue(string $inputFieldName): mixed
    // {
    //     return match ($inputFieldName) {
    //         // By default fetch top-level comments
    //         'parentID' => 0,
    //         default => parent::getInputFieldDefaultValue($inputFieldName)
    //     };
    // }
}