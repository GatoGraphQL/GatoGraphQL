<?php
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;

class PoP_ContentPostLinks_DataLoad_FieldResolver_Posts extends AbstractDBDataFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'excerpt' => SchemaDefinition::TYPE_STRING,
            'content' => SchemaDefinition::TYPE_STRING,
            'linkcontent' => SchemaDefinition::TYPE_STRING,
            'linkaccess' => SchemaDefinition::TYPE_ENUM,
            'linkAccessByName' => SchemaDefinition::TYPE_STRING,
            'linkcategories' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ENUM),
            'linkCategoriesByName' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'hasLinkCategories' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'content',
            'hasLinkCategories',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getSchemaDefinitionEnumName(TypeResolverInterface $typeResolver, string $fieldName): ?string
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

    protected function getSchemaDefinitionEnumValues(TypeResolverInterface $typeResolver, string $fieldName): ?array
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
    public function resolveCanProcessResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
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
            $post = $resultItem;
            $categoryapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
            return defined('POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS') && POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS && $categoryapi->hasCategory(POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS, $typeResolver->getID($post));
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $resultItem;
        switch ($fieldName) {
            // Override fields for Links
            case 'excerpt':
                return PoP_ContentPostLinks_Utils::getLinkExcerpt($post);
            case 'content':
                return PoP_ContentPostLinks_Utils::getLinkContent($post);

            case 'linkcontent':
                return PoP_ContentPostLinks_Utils::getLinkContent($post, true);

            case 'linkaccess':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_LINKACCESS, true);

            case 'linkAccessByName':
                $selected = $typeResolver->resolveValue($post, 'linkaccess', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $linkaccess = new GD_FormInput_LinkAccessDescription($params);
                return $linkaccess->getSelectedValue();

            case 'linkcategories':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_LINKCATEGORIES);

            case 'linkCategoriesByName':
                $selected = $typeResolver->resolveValue($post, 'linkcategories', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $linkcategories = new GD_FormInput_LinkCategories($params);
                return $linkcategories->getSelectedValue();

            case 'hasLinkCategories':
                if ($typeResolver->resolveValue($post, 'linkcategories', $variables, $expressions, $options)) {
                    return true;
                }
                return false;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_ContentPostLinks_DataLoad_FieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, 20);
