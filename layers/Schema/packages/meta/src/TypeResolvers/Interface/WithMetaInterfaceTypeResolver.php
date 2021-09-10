<?php

declare(strict_types=1);

namespace PoPSchema\Meta\TypeResolvers\Interface;

use PoP\ComponentModel\TypeResolvers\Interface\AbstractInterfaceTypeResolver;

class WithMetaInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'WithMeta';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Fields with meta values', 'custompostmeta');
    }
}
