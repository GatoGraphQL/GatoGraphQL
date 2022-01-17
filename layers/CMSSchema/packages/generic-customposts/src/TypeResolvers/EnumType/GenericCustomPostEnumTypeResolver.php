<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\TypeResolvers\EnumType;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\GenericCustomPosts\Component;
use PoPCMSSchema\GenericCustomPosts\ComponentConfiguration;

class GenericCustomPostEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getGenericCustomPostTypes();
    }
}
