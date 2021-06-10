<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\EventTags\Facades\EventTagTypeAPIFacade;

class CatEventFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(EventTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'catSlugs',
            'catName',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'catSlugs' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'catName' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'catSlugs':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'catSlugs' => $this->translationAPI->__('', ''),
            'catName' => $this->translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $eventTagTypeAPI = EventTagTypeAPIFacade::getInstance();
        $event = $resultItem;
        switch ($fieldName) {
             // Override
            case 'catSlugs':
                $value = array();
                $cats = $eventTypeAPI->getCategories($event);
                foreach ($cats as $cat) {
                    $value[] = $eventTagTypeAPI->getCategorySlug($cat);
                }
                return $value;

            case 'catName':
                $cat = $typeResolver->resolveValue($event, 'mainCategory', $variables, $expressions, $options);
                if (GeneralUtils::isError($cat)) {
                    return $cat;
                } elseif ($cat) {
                    return $eventTagTypeAPI->getTermName($cat, $eventTypeAPI->getEventCategoryTaxonomy());
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
