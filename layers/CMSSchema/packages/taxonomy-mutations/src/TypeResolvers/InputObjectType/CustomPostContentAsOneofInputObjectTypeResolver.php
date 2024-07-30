<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class CustomPostContentAsOneofInputObjectTypeResolver extends AbstractCustomPostContentAsOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostContentInput';
    }
}
