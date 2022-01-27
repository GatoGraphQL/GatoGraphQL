<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class SupportingFeaturedImageInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'SupportingFeaturedImage';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Fields concerning an entity\'s featured image', 'custompostmedia');
    }
}
