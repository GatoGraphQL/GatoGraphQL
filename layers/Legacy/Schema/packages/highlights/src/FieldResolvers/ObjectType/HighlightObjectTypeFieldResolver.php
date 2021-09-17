<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoPSchema\CustomPostMeta\Utils;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;

class HighlightObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected StringScalarTypeResolver $StringScalarTypeResolver,
        protected URLScalarTypeResolver $URLScalarTypeResolver,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            HighlightObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'title',
            'excerpt',
            'content',
            'highlightedpost',
            'highlightedPostURL',
            'highlightedpost',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'title' => $this->StringScalarTypeResolver,
            'excerpt' => $this->StringScalarTypeResolver,
            'content' => $this->StringScalarTypeResolver,
            'highlightedPostURL' => $this->URLScalarTypeResolver,
            'highlightedpost' => $this->instanceManager->getInstance(CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolverClass(CustomPostUnionTypeResolver::class)),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'content',
            'highlightedpost',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'title' => $this->translationAPI->__('', ''),
            'excerpt' => $this->translationAPI->__('', ''),
            'content' => $this->translationAPI->__('', ''),
            'highlightedpost' => $this->translationAPI->__('', ''),
            'highlightedPostURL' => $this->translationAPI->__('', ''),
            'highlightedpost' => $this->translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
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
        $highlight = $object;
        switch ($fieldName) {
         // Override fields for Highlights
         // The Stance has no title, so return the excerpt instead.
         // Needed for when adding a comment on the Stance, where it will say: Add comment for...
            case 'title':
            case 'excerpt':
            case 'content':
                $value = $customPostTypeAPI->getPlainTextContent($highlight);
                if ($fieldName == 'title') {
                    return limitString($value, 100);
                } elseif ($fieldName == 'excerpt') {
                    return limitString($value, 300);
                }
                return $value;

            case 'highlightedpost':
                return Utils::getCustomPostMeta($objectTypeResolver->getID($highlight), GD_METAKEY_POST_HIGHLIGHTEDPOST, true);

            case 'highlightedPostURL':
                $highlightedPost = $objectTypeResolver->resolveValue($highlight, 'highlightedpost', $variables, $expressions, $options);
                if (GeneralUtils::isError($highlightedPost)) {
                    return $highlightedPost;
                }
                return $customPostTypeAPI->getPermalink($highlightedPost);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
