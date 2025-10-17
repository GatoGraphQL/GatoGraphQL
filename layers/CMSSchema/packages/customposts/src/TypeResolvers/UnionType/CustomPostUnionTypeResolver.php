<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader = null;

    final protected function getCustomPostUnionTypeDataLoader(): CustomPostUnionTypeDataLoader
    {
        if ($this->customPostUnionTypeDataLoader === null) {
            /** @var CustomPostUnionTypeDataLoader */
            $customPostUnionTypeDataLoader = $this->instanceManager->getInstance(CustomPostUnionTypeDataLoader::class);
            $this->customPostUnionTypeDataLoader = $customPostUnionTypeDataLoader;
        }
        return $this->customPostUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'custom post\' type resolvers', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostUnionTypeDataLoader();
    }

    /**
     * Commented out because this is not necessarily true.
     * Eg: it doesn't happen with WooCommerce Products
     * (WooCommerceSimpleProduct, WooCommerceVariableProduct, WooCommerceExternalProduct, WooCommerceGroupProduct),
     * which are returned within the CustomPostUnion yet they
     * do not implement the CustomPost interface.
     */
    // /**
    //  * @return InterfaceTypeResolverInterface[]
    //  */
    // public function getUnionTypeInterfaceTypeResolvers(): array
    // {
    //     return [
    //         $this->getCustomPostInterfaceTypeResolver(),
    //     ];
    // }
}
