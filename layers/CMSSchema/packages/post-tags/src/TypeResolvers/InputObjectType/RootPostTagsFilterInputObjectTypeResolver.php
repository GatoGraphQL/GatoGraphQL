<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractTagsFilterInputObjectTypeResolver;

class RootPostTagsFilterInputObjectTypeResolver extends AbstractTagsFilterInputObjectTypeResolver implements PostTagsFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootPostTagsFilterInput';
    }
}
