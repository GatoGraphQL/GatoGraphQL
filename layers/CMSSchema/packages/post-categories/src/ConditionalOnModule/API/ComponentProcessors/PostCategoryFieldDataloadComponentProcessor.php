<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Categories\ConditionalOnModule\API\ComponentProcessors\AbstractFieldDataloadComponentProcessor;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;

class PostCategoryFieldDataloadComponentProcessor extends AbstractFieldDataloadComponentProcessor
{
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;

    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORY:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return $this->getPostCategoryObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
