<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class CustomPostListUserObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?UserCustomPostsFilterInputObjectTypeResolver $userCustomPostsFilterInputObjectTypeResolver = null;

    final public function setUserCustomPostsFilterInputObjectTypeResolver(UserCustomPostsFilterInputObjectTypeResolver $userCustomPostsFilterInputObjectTypeResolver): void
    {
        $this->userCustomPostsFilterInputObjectTypeResolver = $userCustomPostsFilterInputObjectTypeResolver;
    }
    final protected function getUserCustomPostsFilterInputObjectTypeResolver(): UserCustomPostsFilterInputObjectTypeResolver
    {
        return $this->userCustomPostsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(UserCustomPostsFilterInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getUserCustomPostsFilterInputObjectTypeResolver();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->__('Custom posts by the user', 'pop-users'),
            'customPostCount' => $this->__('Number of custom posts by the user', 'pop-users'),
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

        $user = $object;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
                $query['authors'] = [$objectTypeResolver->getID($user)];
                break;
        }

        return $query;
    }
}
