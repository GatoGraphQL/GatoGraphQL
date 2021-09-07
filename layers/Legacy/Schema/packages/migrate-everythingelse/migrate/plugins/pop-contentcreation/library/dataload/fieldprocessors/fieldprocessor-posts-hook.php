<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\Interface\IsCustomPostInterfaceTypeResolver;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;

class GD_ContentCreation_DataLoad_FieldResolver_Posts extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(
            IsCustomPostInterfaceTypeResolver::class,
        );
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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'titleEdit' => SchemaDefinition::TYPE_STRING,
            'contentEditor' => SchemaDefinition::TYPE_STRING,
            'contentEdit' => SchemaDefinition::TYPE_STRING,
            'editURL' => SchemaDefinition::TYPE_URL,
            'deleteURL' => SchemaDefinition::TYPE_URL,
            default => parent::getSchemaFieldType($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'coauthors' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'titleEdit' => $translationAPI->__('', ''),
            'contentEditor' => $translationAPI->__('', ''),
            'contentEdit' => $translationAPI->__('', ''),
            'editURL' => $translationAPI->__('', ''),
            'deleteURL' => $translationAPI->__('', ''),
            'coauthors' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmseditpostsapi = \PoP\EditPosts\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {

            case 'titleEdit':
                if (gdCurrentUserCanEdit($relationalTypeResolver->getID($post))) {
                    return $customPostTypeAPI->getTitle($post);
                }
                return '';

            case 'contentEditor':
                if (gdCurrentUserCanEdit($relationalTypeResolver->getID($post))) {
                   return $cmseditpostsapi->getPostEditorContent($relationalTypeResolver->getID($post));
                }
                return '';

            case 'contentEdit':
                if (gdCurrentUserCanEdit($relationalTypeResolver->getID($post))) {
                    return $customPostTypeAPI->getContent($post);
                }
                return '';

            case 'editURL':
                return urldecode($cmseditpostsapi->getEditPostLink($relationalTypeResolver->getID($post)));

            case 'deleteURL':
                return $cmseditpostsapi->getDeletePostLink($relationalTypeResolver->getID($post));

            case 'coauthors':
                $authors = $relationalTypeResolver->resolveValue($resultItem, FieldQueryInterpreterFacade::getInstance()->getField('authors', $fieldArgs), $variables, $expressions, $options);

                // This function only makes sense when the user is logged in
                $vars = ApplicationState::getVars();
                if ($vars['global-userstate']['is-user-logged-in']) {
                    $pos = array_search($vars['global-userstate']['current-user-id'], $authors);
                    if ($pos !== false) {
                        array_splice($authors, $pos, 1);
                    }
                }
                return $authors;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'coauthors':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

// Static Initialization: Attach
(new GD_ContentCreation_DataLoad_FieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
