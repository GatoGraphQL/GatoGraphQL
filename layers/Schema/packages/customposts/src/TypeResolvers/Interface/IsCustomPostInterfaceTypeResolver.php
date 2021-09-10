<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\Interface;

use PoP\ComponentModel\TypeResolvers\Interface\AbstractInterfaceTypeResolver;

class IsCustomPostInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'IsCustomPost';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Entities representing a custom post', 'customposts');
    }
}
