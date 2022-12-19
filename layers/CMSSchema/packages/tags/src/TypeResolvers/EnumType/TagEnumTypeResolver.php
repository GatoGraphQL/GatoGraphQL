<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\EnumType;

use PoP\ComponentModel\App;
use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class TagEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getQueryableTagTaxonomies();
    }
}
