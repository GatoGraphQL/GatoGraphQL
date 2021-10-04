<?php

declare(strict_types=1);

namespace PoPSchema\Stances\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Stances\ComponentConfiguration;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected StanceObjectTypeResolver $stanceObjectTypeResolver;
    
    #[Required]
    final public function autowireCustomPostObjectTypeFieldResolver(
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        StanceObjectTypeResolver $stanceObjectTypeResolver,
    ): void {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->stanceObjectTypeResolver = $stanceObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'stances',
            'hasStances',
            'stanceProCount',
            'stanceNeutralCount',
            'stanceAgainstCount',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'stances' => $this->stanceObjectTypeResolver,
            'hasStances' => $this->booleanScalarTypeResolver,
            'stanceProCount' => $this->intScalarTypeResolver,
            'stanceNeutralCount' => $this->intScalarTypeResolver,
            'stanceAgainstCount' => $this->intScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'hasStances',
            'stanceProCount',
            'stanceNeutralCount',
            'stanceAgainstCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'stances'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'stances' => $this->translationAPI->__('', ''),
            'hasStances' => $this->translationAPI->__('', ''),
            'stanceProCount' => $this->translationAPI->__('', ''),
            'stanceNeutralCount' => $this->translationAPI->__('', ''),
            'stanceAgainstCount' => $this->translationAPI->__('', ''),
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
        $customPost = $object;
        switch ($fieldName) {
            case 'stances':
                $query = array(
                    'limit' => ComponentConfiguration::getStanceListDefaultLimit(),
                    'orderby' => $this->nameResolver->getName('popcms:dbcolumn:orderby:customposts:date'),
                    'order' => 'ASC',
                );
                \UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $objectTypeResolver->getID($customPost));

                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'hasStances':
                $referencedby = $objectTypeResolver->resolveValue($object, 'stances', $variables, $expressions, $options);
                return !empty($referencedby);

            case 'stanceProCount':
            case 'stanceNeutralCount':
            case 'stanceAgainstCount':
                $cats = array(
                    'stanceProCount' => POP_USERSTANCE_TERM_STANCE_PRO,
                    'stanceNeutralCount' => POP_USERSTANCE_TERM_STANCE_NEUTRAL,
                    'stanceAgainstCount' => POP_USERSTANCE_TERM_STANCE_AGAINST,
                );

                $query = array();
                \UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $objectTypeResolver->getID($customPost));

                // Override the category
                $query['tax-query'][] = [
                    'taxonomy' => POP_USERSTANCE_TAXONOMY_STANCE,
                    'terms'    => $cats[$fieldName],
                ];

                // // All results
                // $query['limit'] = 0;

                return $customPostTypeAPI->getCustomPostCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
