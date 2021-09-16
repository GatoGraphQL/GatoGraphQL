<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'excerpt' => SchemaDefinition::TYPE_STRING,
            'content' => SchemaDefinition::TYPE_STRING,
            'linkcontent' => SchemaDefinition::TYPE_STRING,
            'linkaccess' => SchemaDefinition::TYPE_ENUM,
            'linkAccessByName' => SchemaDefinition::TYPE_STRING,
            'linkcategories' => SchemaDefinition::TYPE_ENUM,
            'linkCategoriesByName' => SchemaDefinition::TYPE_STRING,
            'hasLinkCategories' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'content',
            'hasLinkCategories',
                => SchemaTypeModifiers::NON_NULLABLE,
            'linkcategories',
            'linkCategoriesByName'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'excerpt' => $translationAPI->__('', ''),
            'content' => $translationAPI->__('', ''),
            'linkcontent' => $translationAPI->__('', ''),
            'linkaccess' => $translationAPI->__('', ''),
            'linkAccessByName' => $translationAPI->__('', ''),
            'linkcategories' => $translationAPI->__('', ''),
            'linkCategoriesByName' => $translationAPI->__('', ''),
            'hasLinkCategories' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    protected function getSchemaDefinitionEnumName(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
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

    protected function getSchemaDefinitionEnumValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
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
        RelationalTypeResolverInterface $relationalTypeResolver,
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
            return defined('POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS') && POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS && $postCategoryTypeAPI->hasCategory(POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS, $relationalTypeResolver->getID($post));
        }
        return true;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
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
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_LINKACCESS, true);

            case 'linkAccessByName':
                $selected = $relationalTypeResolver->resolveValue($post, 'linkaccess', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $linkaccess = new GD_FormInput_LinkAccessDescription($params);
                return $linkaccess->getSelectedValue();

            case 'linkcategories':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_LINKCATEGORIES);

            case 'linkCategoriesByName':
                $selected = $relationalTypeResolver->resolveValue($post, 'linkcategories', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $linkcategories = new GD_FormInput_LinkCategories($params);
                return $linkcategories->getSelectedValue();

            case 'hasLinkCategories':
                if ($relationalTypeResolver->resolveValue($post, 'linkcategories', $variables, $expressions, $options)) {
                    return true;
                }
                return false;
        }

        return parent::resolveValue($relationalTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_ContentPostLinks_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
