<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\RootTypeDataLoader;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    public const HOOK_DESCRIPTION = __CLASS__ . ':description';
    protected RootTypeDataLoader $rootTypeDataLoader;

    #[Required]
    final public function autowireRootObjectTypeResolver(
        RootTypeDataLoader $rootTypeDataLoader,
    ): void {
        $this->rootTypeDataLoader = $rootTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'Root';
    }

    public function getTypeDescription(): ?string
    {
        return $this->hooksAPI->applyFilters(
            self::HOOK_DESCRIPTION,
            $this->translationAPI->__('Root type, starting from which the query is executed', 'api')
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
        return $this->rootTypeDataLoader;
    }
}
