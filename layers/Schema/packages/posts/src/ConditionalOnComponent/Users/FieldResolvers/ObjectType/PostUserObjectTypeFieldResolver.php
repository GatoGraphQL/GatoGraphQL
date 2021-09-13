<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Posts\FieldResolvers\ObjectType\AbstractPostObjectTypeFieldResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PostUserObjectTypeFieldResolver extends AbstractPostObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'posts' => $this->translationAPI->__('Posts by the user', 'users'),
            'postCount' => $this->translationAPI->__('Number of posts by the user', 'users'),
            'postsForAdmin' => $this->translationAPI->__('[Unrestricted] Posts by the user', 'users'),
            'postCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of posts by the user', 'users'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $query = parent::getQuery($objectTypeResolver, $resultItem, $fieldName, $fieldArgs);

        $user = $resultItem;
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
            case 'postsForAdmin':
            case 'postCountForAdmin':
                $query['authors'] = [$objectTypeResolver->getID($user)];
                break;
        }

        return $query;
    }
}
