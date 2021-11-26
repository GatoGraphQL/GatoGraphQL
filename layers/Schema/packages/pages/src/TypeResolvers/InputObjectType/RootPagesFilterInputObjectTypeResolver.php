<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\InputObjectType;

class RootPagesFilterInputObjectTypeResolver extends AbstractPagesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootPagesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter pages', 'pages');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }
}
