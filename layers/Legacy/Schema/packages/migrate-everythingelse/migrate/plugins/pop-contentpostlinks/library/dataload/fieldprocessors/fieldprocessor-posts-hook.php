<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

class PoP_ContentPostLinks_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
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
            'excerpt',
            'content',
            'linkcontent',
            'linkaccess',
            'linkAccessByName',
            'linkcategories',
            'linkCategoriesByName',
            'hasLinkCategories',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'excerpt' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'content' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'linkcontent' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'linkAccessByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'linkCategoriesByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasLinkCategories' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'content',
            'hasLinkCategories',
                => SchemaTypeModifiers::NON_NULLABLE,
            'linkcategories',
            'linkCategoriesByName'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'excerpt' => $translationAPI->__('', ''),
            'content' => $translationAPI->__('', ''),
            'linkcontent' => $translationAPI->__('', ''),
            'linkaccess' => $translationAPI->__('', ''),
            'linkAccessByName' => $translationAPI->__('', ''),
            'linkcategories' => $translationAPI->__('', ''),
            'linkCategoriesByName' => $translationAPI->__('', ''),
            'hasLinkCategories' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    // @todo: Migrate to returning an EnumTypeResolverClass in getFieldTypeResolver, then delete this function
    //        Until then, this logic is not working (this function is not invoked anymore)
    protected function getSchemaDefinitionEnumName(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'linkaccess':
            case 'linkcategories':
                $input_names = [
                    'linkaccess' => 'LinkAccess',
                    'linkcategories' => 'LinkCategory',
                ];
                return $input_names[$fieldName];
        }
        return null;
    }

    // @todo: Migrate to returning an EnumTypeResolverClass in getFieldTypeResolver, then delete this function
    //        Until then, this logic is not working (this function is not invoked anymore)
    protected function getSchemaDefinitionEnumValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'linkaccess':
            case 'linkcategories':
                $input_classes = [
                    'linkaccess' => GD_FormInput_LinkAccessDescription::class,
                    'linkcategories' => GD_FormInput_LinkCategories::class,
                ];
                $class = $input_classes[$fieldName];
                return array_keys((new $class())->getAllValues());
        }
        return null;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        if (
            in_array(
                $fieldName,
                [
                    'excerpt',
                    'content',
                ]
            )
        ) {
            $post = $object;
            $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
            return defined('POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS') && POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS && $postCategoryTypeAPI->hasCategory(POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS, $objectTypeResolver->getID($post));
        }
        return true;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            // Override fields for Links
            case 'excerpt':
                return PoP_ContentPostLinks_Utils::getLinkExcerpt($post);
            case 'content':
                return PoP_ContentPostLinks_Utils::getLinkContent($post);

            case 'linkcontent':
                return PoP_ContentPostLinks_Utils::getLinkContent($post, true);

            case 'linkaccess':
                return \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_LINKACCESS, true);

            case 'linkAccessByName':
                $selected = $objectTypeResolver->resolveValue($post, 'linkaccess', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                $params = array(
                    'selected' => $selected
                );
                $linkaccess = new GD_FormInput_LinkAccessDescription($params);
                return $linkaccess->getSelectedValue();

            case 'linkcategories':
                return \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_LINKCATEGORIES);

            case 'linkCategoriesByName':
                $selected = $objectTypeResolver->resolveValue($post, 'linkcategories', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                $params = array(
                    'selected' => $selected
                );
                $linkcategories = new GD_FormInput_LinkCategories($params);
                return $linkcategories->getSelectedValue();

            case 'hasLinkCategories':
                if ($objectTypeResolver->resolveValue($post, 'linkcategories', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options)) {
                    return true;
                }
                return false;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

// Static Initialization: Attach
(new PoP_ContentPostLinks_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
