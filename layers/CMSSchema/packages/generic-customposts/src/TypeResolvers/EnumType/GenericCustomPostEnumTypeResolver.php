<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\TypeResolvers\EnumType;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\GenericCustomPosts\Module;
use PoPCMSSchema\GenericCustomPosts\ModuleConfiguration;

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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getGenericCustomPostTypes();
    }
}
