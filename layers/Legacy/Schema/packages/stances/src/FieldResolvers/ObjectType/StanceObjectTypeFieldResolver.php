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
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
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
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
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
        $stance = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'categories':
                /**
                 * @todo TaxonomyTypeAPI was removed! This stale code must be fixed
                 */
                return $this->getTaxonomyTypeAPI()->getCustomPostTaxonomyTerms(
                    $objectTypeResolver->getID($stance),
                    [
                        'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    ],
                    [
                        QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
                    ]
                ) ?? [];

            case 'catSlugs':
                /**
                 * @todo TaxonomyTypeAPI was removed! This stale code must be fixed
                 */
                return $this->getTaxonomyTypeAPI()->getCustomPostTaxonomyTerms(
                    $objectTypeResolver->getID($stance),
                    [
                        'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    ],
                    [
                        QueryOptions::RETURN_TYPE => ReturnTypes::SLUGS,
                    ]
                ) ?? [];

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
                $value = $customPostTypeAPI->getContent($stance);
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
