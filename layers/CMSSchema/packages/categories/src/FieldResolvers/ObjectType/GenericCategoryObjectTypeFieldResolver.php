<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;

    final public function setQueryableCategoryTypeAPI(QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI): void
    {
        $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
    }
    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        if ($this->queryableCategoryTypeAPI === null) {
            /** @var QueryableCategoryTypeAPIInterface */
            $queryableCategoryTypeAPI = $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
            $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
        }
        return $this->queryableCategoryTypeAPI;
    }
    final public function setGenericCategoryObjectTypeResolver(GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCategoryObjectTypeResolver::class,
        ];
    }
}
