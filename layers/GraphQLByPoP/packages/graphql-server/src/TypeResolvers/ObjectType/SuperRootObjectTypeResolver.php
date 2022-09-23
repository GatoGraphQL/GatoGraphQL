<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\SuperRoot;
use GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SuperRootTypeDataLoader;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

class SuperRootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    private ?SuperRootTypeDataLoader $superRootTypeDataLoader = null;
    private ?MandatoryOperationDirectiveResolverRegistryInterface $mandatoryOperationDirectiveResolverRegistry = null;

    final public function setSuperRootTypeDataLoader(SuperRootTypeDataLoader $superRootTypeDataLoader): void
    {
        $this->superRootTypeDataLoader = $superRootTypeDataLoader;
    }
    final protected function getSuperRootTypeDataLoader(): SuperRootTypeDataLoader
    {
        /** @var SuperRootTypeDataLoader */
        return $this->superRootTypeDataLoader ??= $this->instanceManager->getInstance(SuperRootTypeDataLoader::class);
    }
    final public function setMandatoryOperationDirectiveResolverRegistry(MandatoryOperationDirectiveResolverRegistryInterface $mandatoryOperationDirectiveResolverRegistry): void
    {
        $this->mandatoryOperationDirectiveResolverRegistry = $mandatoryOperationDirectiveResolverRegistry;
    }
    final protected function getMandatoryOperationDirectiveResolverRegistry(): MandatoryOperationDirectiveResolverRegistryInterface
    {
        /** @var MandatoryOperationDirectiveResolverRegistryInterface */
        return $this->mandatoryOperationDirectiveResolverRegistry ??= $this->instanceManager->getInstance(MandatoryOperationDirectiveResolverRegistryInterface::class);
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

    /**
     * Provide the mandatory directives for Operations.
     *
     * @return FieldDirectiveResolverInterface[]
     */
    protected function getMandatoryFieldOrOperationDirectiveResolvers(): array
    {
        return $this->getMandatoryOperationDirectiveResolverRegistry()->getMandatoryOperationDirectiveResolvers();
    }
}
