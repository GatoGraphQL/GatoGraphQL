<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMeta\Module;
use PoPCMSSchema\CustomPostMeta\ModuleConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class CustomPostMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getCustomPostMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getCustomPostMetaBehavior();
    }
}
