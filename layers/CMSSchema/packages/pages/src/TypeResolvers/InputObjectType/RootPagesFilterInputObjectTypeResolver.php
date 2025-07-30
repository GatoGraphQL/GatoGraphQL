<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractWithParentCustomPostsFilterInputObjectTypeResolver;

class RootPagesFilterInputObjectTypeResolver extends AbstractWithParentCustomPostsFilterInputObjectTypeResolver
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
