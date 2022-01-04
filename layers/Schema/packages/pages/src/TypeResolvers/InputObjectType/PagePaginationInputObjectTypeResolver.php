<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPSchema\Pages\Component;
use PoPSchema\Pages\ComponentConfiguration;

class PagePaginationInputObjectTypeResolver extends CustomPostPaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PagePaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate pages', 'pages');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getPageListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getPageListMaxLimit();
    }
}
