<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Avatar_DataLoad_ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'userphoto',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'userphoto' => \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
			'userphoto' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $user = $object;
        switch ($field->getName()) {
            case 'userphoto':
                $userphoto = gdGetAvatarPhotoinfo($objectTypeResolver->getID($user));
                return array(
                    'src' => $userphoto['src'],
                    'width' => $userphoto['width'],
                    'height' => $userphoto['height']
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}

// Static Initialization: Attach
(new PoP_Avatar_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
