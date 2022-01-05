<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeResolvers\EnumType;

use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\GenericCustomPosts\Component;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;

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
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getGenericCustomPostTypes();
    }
}
