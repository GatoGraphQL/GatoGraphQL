<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

class RootPredefinedCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements CustomPostsFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootPredefinedCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter predefined custom posts', 'customposts');
    }
}
