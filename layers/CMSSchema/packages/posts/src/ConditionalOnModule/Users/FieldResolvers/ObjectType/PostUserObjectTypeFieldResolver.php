<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Posts\FieldResolvers\ObjectType\AbstractPostObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PostUserObjectTypeFieldResolver extends AbstractPostObjectTypeFieldResolver
{
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'posts' => $this->__('Posts by the user', 'users'),
            'postCount' => $this->__('Number of posts by the user', 'users'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldDataAccessor);

        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'posts':
            case 'postCount':
                $query['authors'] = [$objectTypeResolver->getID($user)];
                break;
        }

        return $query;
    }
}
