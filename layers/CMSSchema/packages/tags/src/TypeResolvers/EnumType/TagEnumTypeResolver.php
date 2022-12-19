<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\EnumType;

use PoP\ComponentModel\App;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\Tags\ModuleConfiguration as TagsModuleConfiguration;
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
        /** @var TagsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagsModule::class)->getConfiguration();
        return $moduleConfiguration->getQueryableTagTaxonomies();
    }
}
