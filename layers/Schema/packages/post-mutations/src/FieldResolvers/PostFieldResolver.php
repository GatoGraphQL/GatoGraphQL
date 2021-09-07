<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPostMutations\FieldResolvers\AbstractCustomPostFieldResolver;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPSchema\Posts\TypeResolvers\Object\PostTypeResolver;
use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;

class PostFieldResolver extends AbstractCustomPostFieldResolver
{
    use UserStateFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(PostTypeResolver::class);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'update' => $this->translationAPI->__('Update the post', 'post-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'update':
                return UpdatePostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'update':
                return PostTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
