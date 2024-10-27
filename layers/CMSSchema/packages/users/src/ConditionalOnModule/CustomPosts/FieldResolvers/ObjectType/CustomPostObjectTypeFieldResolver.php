<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;
use PoPCMSSchema\Users\FieldResolvers\InterfaceType\WithAuthorInterfaceTypeFieldResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostUserTypeAPIInterface $customPostUserTypeAPI = null;
    private ?WithAuthorInterfaceTypeFieldResolver $withAuthorInterfaceTypeFieldResolver = null;

    final protected function getCustomPostUserTypeAPI(): CustomPostUserTypeAPIInterface
    {
        if ($this->customPostUserTypeAPI === null) {
            /** @var CustomPostUserTypeAPIInterface */
            $customPostUserTypeAPI = $this->instanceManager->getInstance(CustomPostUserTypeAPIInterface::class);
            $this->customPostUserTypeAPI = $customPostUserTypeAPI;
        }
        return $this->customPostUserTypeAPI;
    }
    final protected function getWithAuthorInterfaceTypeFieldResolver(): WithAuthorInterfaceTypeFieldResolver
    {
        if ($this->withAuthorInterfaceTypeFieldResolver === null) {
            /** @var WithAuthorInterfaceTypeFieldResolver */
            $withAuthorInterfaceTypeFieldResolver = $this->instanceManager->getInstance(WithAuthorInterfaceTypeFieldResolver::class);
            $this->withAuthorInterfaceTypeFieldResolver = $withAuthorInterfaceTypeFieldResolver;
        }
        return $this->withAuthorInterfaceTypeFieldResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getWithAuthorInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'author',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'author' => $this->__('The post\'s author', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'author':
                /** @var string|int */
                return $this->getCustomPostUserTypeAPI()->getAuthorID($object);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
