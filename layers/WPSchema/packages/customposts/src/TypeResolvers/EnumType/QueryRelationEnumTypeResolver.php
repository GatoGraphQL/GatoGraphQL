<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPWPSchema\CustomPosts\Constants\QueryRelations;

/**
 * Query "relation" arg, as explained here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#custom-field-post-meta-parameters
 */
class QueryRelationEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'QueryRelationEnum';
    }

    public function getTypeDescription(): string
    {
        return $this->getTranslationAPI()->__('The logical relationship between array values in query args (for meta query, date parameters, and others) when there is more than one', 'customposts');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            QueryRelations::AND,
            QueryRelations::OR,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            QueryRelations::AND => $this->getTranslationAPI()->__('`AND` relation', 'schema-commons'),
            QueryRelations::OR => $this->getTranslationAPI()->__('`OR` relation', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
