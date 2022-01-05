<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\Engine\App;
use PoPSchema\CustomPosts\Component;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CustomPostPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate custom posts', 'customposts');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostListMaxLimit();
    }
}
