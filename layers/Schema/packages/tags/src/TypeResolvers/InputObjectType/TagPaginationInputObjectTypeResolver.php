<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPSchema\Tags\Component;
use PoPSchema\Tags\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class TagPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate tags', 'tags');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTagListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTagListMaxLimit();
    }
}
