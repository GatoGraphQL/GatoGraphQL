<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

class RootPagesFilterInputObjectTypeResolver extends AbstractPagesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootPagesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter pages', 'pages');
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }
}
