<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\RootTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

class RootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    public final const HOOK_DESCRIPTION = __CLASS__ . ':description';

    private ?RootTypeDataLoader $rootTypeDataLoader = null;

    final public function setRootTypeDataLoader(RootTypeDataLoader $rootTypeDataLoader): void
    {
        $this->rootTypeDataLoader = $rootTypeDataLoader;
    }
    final protected function getRootTypeDataLoader(): RootTypeDataLoader
    {
        return $this->rootTypeDataLoader ??= $this->instanceManager->getInstance(RootTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Root';
    }

    public function getTypeDescription(): ?string
    {
        return App::applyFilters(
            self::HOOK_DESCRIPTION,
            $this->__('Root type, starting from which the query is executed', 'api')
        );
    }

    public function getID(object $object): string | int | null
    {
        /** @var Root */
        $root = $object;
        return $root->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootTypeDataLoader();
    }
}
