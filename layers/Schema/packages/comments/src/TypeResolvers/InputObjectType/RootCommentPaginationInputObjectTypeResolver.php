<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
use PoPSchema\Comments\Component;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class RootCommentPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCommentPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate comments', 'comments');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getRootCommentListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCommentListMaxLimit();
    }
}
