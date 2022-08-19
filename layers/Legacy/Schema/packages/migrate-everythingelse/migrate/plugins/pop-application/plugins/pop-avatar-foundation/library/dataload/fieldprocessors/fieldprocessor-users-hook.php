<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Application_UserAvatar_DataLoad_ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
			'avatar',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'avatar' => \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
			'avatar' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'avatar' => [
                'size' => $this->intScalarTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['avatar' => 'size'] => $this->getTranslationAPI()->__('Avatar size, in pixels', ''),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            ['avatar' => 'size'] => \GD_AVATAR_SIZE_60,
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user = $object;
        switch ($field->getName()) {
            case 'avatar':
                // The avatar size is set through fieldArgs
                return gdGetAvatar($objectTypeResolver->getID($user), (int) $fieldDataAccessor->getValue('size'));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

// Static Initialization: Attach
(new PoP_Application_UserAvatar_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
