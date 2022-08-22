<?php

declare(strict_types=1);

namespace PoPSchema\Stances\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Taxonomies\Facades\TaxonomyTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;

class StanceObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            StanceObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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
        return match($fieldName) {
            'stancetarget' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'categories' => $this->getIdScalarTypeResolver(),
            'catSlugs' => $this->getStringScalarTypeResolver(),
            'stance' => $this->getIntScalarTypeResolver(),
            'title' => $this->getStringScalarTypeResolver(),
            'excerpt' => $this->getStringScalarTypeResolver(),
            'content' => $this->getStringScalarTypeResolver(),
            'hasStanceTarget' => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'content',
            'hasStanceTarget'
                => SchemaTypeModifiers::NON_NULLABLE,
            'categories',
            'catSlugs'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'categories' => $this->getTranslationAPI()->__('', ''),
            'catSlugs' => $this->getTranslationAPI()->__('', ''),
            'stance' => $this->getTranslationAPI()->__('', ''),
            'title' => $this->getTranslationAPI()->__('', ''),
            'excerpt' => $this->getTranslationAPI()->__('', ''),
            'content' => $this->getTranslationAPI()->__('', ''),
            'stancetarget' => $this->getTranslationAPI()->__('', ''),
            'hasStanceTarget' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $taxonomyapi = TaxonomyTypeAPIFacade::getInstance();
        $stance = $object;
        switch ($fieldDataAccessor->getFieldName()) {
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
                return $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        'mainCategory',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );

            // The Stance has no title, so return the excerpt instead.
            // Needed for when adding a comment on the Stance, where it will say: Add comment for...
            case 'title':
            case 'excerpt':
            case 'content':
                // Add the quotes around the content for the Stance
                $value = $customPostTypeAPI->getRawContent($stance);
                if ($fieldDataAccessor->getFieldName() === 'title') {
                    return limitString($value, 100);
                } elseif ($fieldDataAccessor->getFieldName() === 'excerpt') {
                    return limitString($value, 300);
                }
                return $value;

            case 'stancetarget':
                return Utils::getCustomPostMeta($objectTypeResolver->getID($stance), GD_METAKEY_POST_STANCETARGET, true);

            case 'hasStanceTarget':
                // Cannot use !is_null because getCustomPostMeta returns "" when there's no entry, instead of null
                return $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        'stancetarget',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
