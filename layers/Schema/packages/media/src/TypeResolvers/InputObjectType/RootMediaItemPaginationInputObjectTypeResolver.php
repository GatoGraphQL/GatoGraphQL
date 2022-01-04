<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeResolvers\InputObjectType;

use PoPSchema\Media\Component;
use PoPSchema\Media\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class RootMediaItemPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMediaItemPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate media items', 'media');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getMediaListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getMediaListMaxLimit();
    }
}
