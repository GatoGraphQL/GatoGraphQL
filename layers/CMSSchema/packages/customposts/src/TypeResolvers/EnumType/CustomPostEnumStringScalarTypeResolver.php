<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoP\ComponentModel\App;

class CustomPostEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostEnumString';
    }

    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Custom post types (available for querying via the API), with possible values: `"%s"`.', 'customposts'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getQueryableCustomPostTypes();
    }
}
