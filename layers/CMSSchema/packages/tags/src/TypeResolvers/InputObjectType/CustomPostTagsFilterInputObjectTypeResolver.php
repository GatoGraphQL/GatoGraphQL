<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

class CustomPostTagsFilterInputObjectTypeResolver extends AbstractTagsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostTagsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter tags from a custom post', 'tags');
    }
}
