<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

class RootTagsFilterInputObjectTypeResolver extends AbstractTagsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootTagsFilterInput';
    }
}
