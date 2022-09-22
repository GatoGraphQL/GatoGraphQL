<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\ObjectModels\SuperRoot;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\SuperRootTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

class SuperRootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    private ?SuperRootTypeDataLoader $superRootTypeDataLoader = null;

    final public function setSuperRootTypeDataLoader(SuperRootTypeDataLoader $superRootTypeDataLoader): void
    {
        $this->rootTypeDataLoader = $superRootTypeDataLoader;
    }
    final protected function getSuperRootTypeDataLoader(): SuperRootTypeDataLoader
    {
        /** @var SuperRootTypeDataLoader */
        return $this->rootTypeDataLoader ??= $this->instanceManager->getInstance(SuperRootTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'SuperRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('(Internal) Super Root type, starting from which the query is executed', 'engine');
    }

    public function getID(object $object): string|int|null
    {
        /** @var SuperRoot */
        $superRoot = $object;
        return $superRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSuperRootTypeDataLoader();
    }
}
