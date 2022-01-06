<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Posts\FieldResolvers\ObjectType\AbstractPostObjectTypeFieldResolver;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;

class PostTagListObjectTypeFieldResolver extends AbstractPostObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'posts' => $this->__('Posts which contain this tag', 'pop-taxonomies'),
            'postCount' => $this->__('Number of posts which contain this tag', 'pop-taxonomies'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs);

        $tag = $object;
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
                $query['tag-ids'] = [$objectTypeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
