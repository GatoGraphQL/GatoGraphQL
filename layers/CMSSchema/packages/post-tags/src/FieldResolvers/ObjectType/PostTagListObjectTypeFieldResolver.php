<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\Posts\FieldResolvers\ObjectType\AbstractPostObjectTypeFieldResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;

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
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $field);

        $tag = $object;
        switch ($field->getName()) {
            case 'posts':
            case 'postCount':
                $query['tag-ids'] = [$objectTypeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
