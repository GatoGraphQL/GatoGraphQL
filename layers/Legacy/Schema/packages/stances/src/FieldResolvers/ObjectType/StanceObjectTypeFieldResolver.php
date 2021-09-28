<?php

declare(strict_types=1);

namespace PoPSchema\Stances\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoPSchema\CustomPostMeta\Utils;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;
use PoPSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class StanceObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
    ) {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
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
            StanceObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'categories',
            'catSlugs',
            'stance',
            'title',
            'excerpt',
            'content',
            'stancetarget',
            'hasStanceTarget',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'stancetarget' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'categories' => $this->idScalarTypeResolver,
            'catSlugs' => $this->stringScalarTypeResolver,
            'stance' => $this->intScalarTypeResolver,
            'title' => $this->stringScalarTypeResolver,
            'excerpt' => $this->stringScalarTypeResolver,
            'content' => $this->stringScalarTypeResolver,
            'hasStanceTarget' => $this->booleanScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'content',
            'hasStanceTarget'
                => SchemaTypeModifiers::NON_NULLABLE,
            'categories',
            'catSlugs'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'categories' => $this->translationAPI->__('', ''),
            'catSlugs' => $this->translationAPI->__('', ''),
            'stance' => $this->translationAPI->__('', ''),
            'title' => $this->translationAPI->__('', ''),
            'excerpt' => $this->translationAPI->__('', ''),
            'content' => $this->translationAPI->__('', ''),
            'stancetarget' => $this->translationAPI->__('', ''),
            'hasStanceTarget' => $this->translationAPI->__('', ''),
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
        $taxonomyapi = TaxonomyTypeAPIFacade::getInstance();
        $stance = $object;
        switch ($fieldName) {
            case 'categories':
                return $taxonomyapi->getCustomPostTaxonomyTerms(
                    $objectTypeResolver->getID($stance),
                    POP_USERSTANCE_TAXONOMY_STANCE,
                    [
                        QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
                    ]
                );

            case 'catSlugs':
                return $taxonomyapi->getCustomPostTaxonomyTerms(
                    $objectTypeResolver->getID($stance),
                    POP_USERSTANCE_TAXONOMY_STANCE,
                    [
                        QueryOptions::RETURN_TYPE => ReturnTypes::SLUGS,
                    ]
                );

            case 'stance':
                // The stance is the category
                return $objectTypeResolver->resolveValue($object, 'mainCategory', $variables, $expressions, $options);

            // The Stance has no title, so return the excerpt instead.
            // Needed for when adding a comment on the Stance, where it will say: Add comment for...
            case 'title':
            case 'excerpt':
            case 'content':
                // Add the quotes around the content for the Stance
                $value = $customPostTypeAPI->getPlainTextContent($stance);
                if ($fieldName == 'title') {
                    return limitString($value, 100);
                } elseif ($fieldName == 'excerpt') {
                    return limitString($value, 300);
                }
                return $value;

            case 'stancetarget':
                return Utils::getCustomPostMeta($objectTypeResolver->getID($stance), GD_METAKEY_POST_STANCETARGET, true);

            case 'hasStanceTarget':
                // Cannot use !is_null because getCustomPostMeta returns "" when there's no entry, instead of null
                return $objectTypeResolver->resolveValue($object, 'stancetarget', $variables, $expressions, $options);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
