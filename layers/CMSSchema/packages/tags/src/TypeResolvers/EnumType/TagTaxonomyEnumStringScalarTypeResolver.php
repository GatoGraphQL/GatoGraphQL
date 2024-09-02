<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\EnumType;

class TagTaxonomyEnumStringScalarTypeResolver extends AbstractTagTaxonomyEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagTaxonomyEnumString';
    }

    protected function getRegisteredCustomPostTagTaxonomyNames(): ?array
    {
        return null;
    }
}
