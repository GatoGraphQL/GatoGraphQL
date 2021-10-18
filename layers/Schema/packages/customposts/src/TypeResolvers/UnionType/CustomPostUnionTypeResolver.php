<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    protected CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader;
    protected IsCustomPostInterfaceTypeResolver $isCustomPostInterfaceTypeResolver;

    #[Required]
    final public function autowireCustomPostUnionTypeResolver(
        IsCustomPostInterfaceTypeResolver $isCustomPostInterfaceTypeResolver,
        CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader,
    ): void {
        $this->isCustomPostInterfaceTypeResolver = $isCustomPostInterfaceTypeResolver;
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

    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->isCustomPostInterfaceTypeResolver,
        ];
    }
}
