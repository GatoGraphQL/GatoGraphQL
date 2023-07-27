<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

class CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver extends AbstractFilterCustomPostsByTagsInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'FilterCustomPostsByTagsInput';
    }

    protected function addTagTaxonomyFilterInput(): bool
    {
        return true;
    }
}
