<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Media\Component;
use PoPCMSSchema\Media\ComponentConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class RootMediaItemPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMediaItemPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate media items', 'media');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getMediaListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getMediaListMaxLimit();
    }
}
