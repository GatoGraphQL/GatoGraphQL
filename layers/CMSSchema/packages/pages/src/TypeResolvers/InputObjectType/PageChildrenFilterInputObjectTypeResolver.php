<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

class PageChildrenFilterInputObjectTypeResolver extends AbstractPagesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PageChildrenFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the page children', 'pages');
    }

    protected function addParentInputFields(): bool
    {
        return false;
    }
}
