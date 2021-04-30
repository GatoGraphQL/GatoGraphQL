<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPostMutations\FieldResolvers\AbstractCustomPostFieldResolver;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;

class PostFieldResolver extends AbstractCustomPostFieldResolver
{
    use UserStateFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(PostTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'update' => $this->translationAPI->__('Update the post', 'post-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'update':
                return UpdatePostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'update':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
