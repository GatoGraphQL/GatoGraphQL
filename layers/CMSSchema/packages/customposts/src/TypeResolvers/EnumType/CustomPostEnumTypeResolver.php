<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\App;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class CustomPostEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getQueryableCustomPostTypes();
    }
}
