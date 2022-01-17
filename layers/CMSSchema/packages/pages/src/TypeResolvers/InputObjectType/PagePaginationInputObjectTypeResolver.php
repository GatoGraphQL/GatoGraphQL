<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\Pages\Component;
use PoPCMSSchema\Pages\ComponentConfiguration;

class PagePaginationInputObjectTypeResolver extends CustomPostPaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PagePaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate pages', 'pages');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getPageListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getPageListMaxLimit();
    }
}
