<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_ContentCreation_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'titleEdit',
            'contentEditor',
            'contentEdit',
            'editURL',
            'deleteURL',
            'coauthors',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'titleEdit' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'contentEditor' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'contentEdit' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'editURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'deleteURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'coauthors' => UserObjectTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'coauthors' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'titleEdit' => $translationAPI->__('', ''),
            'contentEditor' => $translationAPI->__('', ''),
            'contentEdit' => $translationAPI->__('', ''),
            'editURL' => $translationAPI->__('', ''),
            'deleteURL' => $translationAPI->__('', ''),
            'coauthors' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmseditpostsapi = \PoP\EditPosts\FunctionAPIFactory::getInstance();
        $post = $object;
        switch ($fieldName) {

            case 'titleEdit':
                if (gdCurrentUserCanEdit($objectTypeResolver->getID($post))) {
                    return $customPostTypeAPI->getTitle($post);
                }
                return '';

            case 'contentEditor':
                if (gdCurrentUserCanEdit($objectTypeResolver->getID($post))) {
                   return $cmseditpostsapi->getPostEditorContent($objectTypeResolver->getID($post));
                }
                return '';

            case 'contentEdit':
                if (gdCurrentUserCanEdit($objectTypeResolver->getID($post))) {
                    return $customPostTypeAPI->getContent($post);
                }
                return '';

            case 'editURL':
                return urldecode($cmseditpostsapi->getEditPostLink($objectTypeResolver->getID($post)));

            case 'deleteURL':
                return $cmseditpostsapi->getDeletePostLink($objectTypeResolver->getID($post));

            case 'coauthors':
                $authors = $objectTypeResolver->resolveValue($object, FieldQueryInterpreterFacade::getInstance()->getField('authors', $fieldArgs), $variables, $expressions, $options);

                // This function only makes sense when the user is logged in
                if (\PoP\Root\App::getState('is-user-logged-in')) {
                    $pos = array_search(\PoP\Root\App::getState('current-user-id'), $authors);
                    if ($pos !== false) {
                        array_splice($authors, $pos, 1);
                    }
                }
                return $authors;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_ContentCreation_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
