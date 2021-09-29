<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    protected CustomPostUnionTypeResolver $customPostUnionTypeResolver;

    #[Required]
    public function autowireCustomPostUnionTypeDataLoader(
        CustomPostUnionTypeResolver $customPostUnionTypeResolver,
    ): void {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->customPostUnionTypeResolver;
    }
}
