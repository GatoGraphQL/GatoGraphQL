<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
use PoPSchema\CustomPostMeta\Component;
use PoPSchema\CustomPostMeta\ComponentConfiguration;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostMetaBehavior();
    }
}
