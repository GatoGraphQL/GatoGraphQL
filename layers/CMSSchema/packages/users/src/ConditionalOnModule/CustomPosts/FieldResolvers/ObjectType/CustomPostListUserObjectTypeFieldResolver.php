<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class CustomPostListUserObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?UserCustomPostsFilterInputObjectTypeResolver $userCustomPostsFilterInputObjectTypeResolver = null;

    final protected function getUserCustomPostsFilterInputObjectTypeResolver(): UserCustomPostsFilterInputObjectTypeResolver
    {
        if ($this->userCustomPostsFilterInputObjectTypeResolver === null) {
            /** @var UserCustomPostsFilterInputObjectTypeResolver */
            $userCustomPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(UserCustomPostsFilterInputObjectTypeResolver::class);
            $this->userCustomPostsFilterInputObjectTypeResolver = $userCustomPostsFilterInputObjectTypeResolver;
        }
        return $this->userCustomPostsFilterInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
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
            case 'customPosts':
            case 'customPostCount':
                $query['authors'] = [$objectTypeResolver->getID($user)];
                break;
        }

        return $query;
    }
}
