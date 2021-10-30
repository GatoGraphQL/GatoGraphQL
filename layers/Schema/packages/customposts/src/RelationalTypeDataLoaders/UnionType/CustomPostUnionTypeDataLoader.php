<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;

    public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostUnionTypeResolver();
    }
}
