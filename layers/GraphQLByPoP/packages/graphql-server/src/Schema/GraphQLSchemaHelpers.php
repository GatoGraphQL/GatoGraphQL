<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

class GraphQLSchemaHelpers
{
    /**
     * Convert the field type from its internal representation
     * to the GraphQL standard representation (eg: "[Post]")
     *
     * If $isNonNullableOrMandatory is `true`, a "!" is added to the type name,
     * to handle both field response and field arguments:
     *
     * - field response: isNonNullable
     * - field argument: isMandatory (its provided value can still be null)
     */
    public static function getMaybeWrappedTypeName(
        string $typeName,
        ?bool $isNonNullableOrMandatory = false,
        ?bool $isArray = false,
        ?bool $isNonNullArrayItems = false,
        ?bool $isArrayOfArrays = false,
        ?bool $isNonNullArrayOfArraysItems = false,
    ): string {
        // Wrap the type with the array brackets
        if ($isArray) {
            if ($isArrayOfArrays) {
                if ($isNonNullArrayOfArraysItems) {
                    $typeName = self::getNonNullTypeName($typeName);
                }
                $typeName = self::getListTypeName($typeName);
            }
            if ($isNonNullArrayItems) {
                $typeName = self::getNonNullTypeName($typeName);
            }
            $typeName = self::getListTypeName($typeName);
        }
        if ($isNonNullableOrMandatory) {
            $typeName = self::getNonNullTypeName($typeName);
        }
        return $typeName;
    }

    public static function getNonNullTypeName(
        string $typeName,
    ): string {
        return sprintf(
            '%s!',
            $typeName
        );
    }

    public static function getListTypeName(
        string $typeName,
    ): string {
        return sprintf(
            '[%s]',
            $typeName
        );
    }

    /**
     * Encode a value using the GraphQL language, as to print it
     * in the schema (eg: as the default value for an input).
     *
     * The JSON and GraphQL language encodings coincide for `Int`,
     * `Float`, `Boolean` and `String`, and lists of them, but not
     * for enum values (which must not be quoted) nor for input
     * objects (whose keys must not be quoted).
     *
     * @todo The values within an input object are always encoded as
     *   non-enum values, as their own type is not known here.
     */
    public static function encodeValueUsingGraphQLLanguage(
        mixed $value,
        bool $isEnumType,
    ): string {
        if (is_array($value)) {
            if (array_values($value) === $value) {
                return sprintf(
                    '[%s]',
                    implode(
                        ', ',
                        array_map(
                            fn (mixed $listItemValue) => self::encodeValueUsingGraphQLLanguage($listItemValue, $isEnumType),
                            $value
                        )
                    )
                );
            }
            $inputObjectFieldEntries = [];
            foreach ($value as $inputFieldName => $inputFieldValue) {
                $inputObjectFieldEntries[] = sprintf(
                    '%s: %s',
                    $inputFieldName,
                    self::encodeValueUsingGraphQLLanguage($inputFieldValue, false)
                );
            }
            return sprintf(
                '{%s}',
                implode(', ', $inputObjectFieldEntries)
            );
        }
        if ($isEnumType && is_string($value)) {
            return $value;
        }
        return (string)json_encode($value);
    }
}
