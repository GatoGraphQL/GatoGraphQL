<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    protected CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader;
    protected InterfaceTypeResolverInterface $interfaceTypeResolver;

    #[Required]
    public function autowireCustomPostUnionTypeResolver(
        InterfaceTypeResolverInterface $interfaceTypeResolver,
        CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader,
    ): void {
        $this->interfaceTypeResolver = $interfaceTypeResolver;
        $this->customPostUnionTypeDataLoader = $customPostUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Union of \'custom post\' type resolvers', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->customPostUnionTypeDataLoader;
    }

    public function getUnionTypeInterfaceTypeResolver(): ?InterfaceTypeResolverInterface
    {
        return $this->interfaceTypeResolver;
    }
}
