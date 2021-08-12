<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\TranslationAPIInterface;
use CastToType;
use DateTime;

class TypeCastingExecuter implements TypeCastingExecuterInterface
{
    public function __construct(private TranslationAPIInterface $translationAPI)
    {
    }

    /**
     * Cast the value to the indicated type, or return Error (with a message) if it fails.
     *
     * An array is not a type. For instance, in `[String]`, the type is `String`,
     * and the array is a modifier.
     * Then, if passing an array, this function will always fail casting
     */
    public function cast(string $type, mixed $value): mixed
    {
        if ($value === null) {
            return new Error(
                'null-cast',
                $this->translationAPI->__('Cannot cast null', 'component-model')
            );
        }

        // Fail if passing an array for unsupporting types
        if (
            (is_array($value) || is_object($value)) && in_array($type, [
            SchemaDefinition::TYPE_ANY_SCALAR,
            SchemaDefinition::TYPE_ID,
            SchemaDefinition::TYPE_ARRAY_KEY,
            SchemaDefinition::TYPE_STRING,
            SchemaDefinition::TYPE_URL,
            SchemaDefinition::TYPE_EMAIL,
            SchemaDefinition::TYPE_IP,
            SchemaDefinition::TYPE_ENUM,
            SchemaDefinition::TYPE_DATE,
            SchemaDefinition::TYPE_INT,
            SchemaDefinition::TYPE_FLOAT,
            SchemaDefinition::TYPE_BOOL,
            SchemaDefinition::TYPE_TIME,
            ])
        ) {
            $entity = is_array($value) ? 'array' : 'object';
            return new Error(
                sprintf('%s-cast', $entity),
                sprintf(
                    $this->translationAPI->__('An %s cannot be casted to type \'%s\'', 'component-model'),
                    $entity,
                    $type
                )
            );
        }

        switch ($type) {
            case SchemaDefinition::TYPE_MIXED:
                return $value;
            case SchemaDefinition::TYPE_ANY_SCALAR:
                return $value;
            case SchemaDefinition::TYPE_ID:
            case SchemaDefinition::TYPE_ARRAY_KEY:
                // Type ID in GraphQL spec: only String or Int allowed.
                // @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
                if (is_float($value) || is_bool($value)) {
                    return new Error(
                        'id-cast',
                        $this->translationAPI->__('Type ID in GraphQL spec: only String or Int allowed', 'component-model')
                    );
                }
                return $value;
            case SchemaDefinition::TYPE_STRING:
                return (string) $value;
            case SchemaDefinition::TYPE_URL:
            case SchemaDefinition::TYPE_EMAIL:
            case SchemaDefinition::TYPE_IP:
                // Validate they are right
                $filters = [
                    SchemaDefinition::TYPE_URL => FILTER_VALIDATE_URL,
                    SchemaDefinition::TYPE_EMAIL => FILTER_VALIDATE_EMAIL,
                    SchemaDefinition::TYPE_IP => FILTER_VALIDATE_IP,
                ];
                $valid = filter_var($value, $filters[$type]);
                if ($valid === false) {
                    $elements = [
                        SchemaDefinition::TYPE_URL => $this->translationAPI->__('URL', 'component-model'),
                        SchemaDefinition::TYPE_EMAIL => $this->translationAPI->__('email', 'component-model'),
                        SchemaDefinition::TYPE_IP => $this->translationAPI->__('IP', 'component-model'),
                    ];
                    return new Error(
                        sprintf('%s-cast', $elements[$type]),
                        sprintf(
                            $this->translationAPI->__('The format for the %s \'%s\' is not right', 'component-model'),
                            $elements[$type],
                            $value
                        )
                    );
                }
                return $value;
            case SchemaDefinition::TYPE_ENUM:
                // It is not possible to validate this, so just return whatever it gets
                return $value;
            case SchemaDefinition::TYPE_OBJECT:
            case SchemaDefinition::TYPE_INPUT_OBJECT:
                if (!(is_object($value) || is_array($value))) {
                    return new Error(
                        'object-cast',
                        sprintf(
                            $this->translationAPI->__('An object cannot be casted from \'%s\'', 'component-model'),
                            $value
                        )
                    );
                }
                return $value;
            case SchemaDefinition::TYPE_DATE:
                if (!is_string($value)) {
                    return new Error(
                        'date-cast',
                        $this->translationAPI->__('Date must be provided as a string', 'component-model')
                    );
                }
                // Validate that the format is 'Y-m-d'
                // Taken from https://stackoverflow.com/a/13194398
                $dt = DateTime::createFromFormat("Y-m-d", $value);
                if ($dt === false || array_sum($dt::getLastErrors())) {
                    return new Error(
                        'date-cast',
                        sprintf(
                            $this->translationAPI->__('Date format must be \'%s\'', 'component-model'),
                            'Y-m-d'
                        )
                    );
                }
                return $value;
            case SchemaDefinition::TYPE_INT:
                return (int) CastToType::_int($value);
            case SchemaDefinition::TYPE_FLOAT:
                return (float) CastToType::_float($value);
            case SchemaDefinition::TYPE_BOOL:
                // Watch out! In Library CastToType, an empty string is not false, but it's NULL
                // But for us it must be false, since calling query ?query=and([true,false]) gets transformed to the $field string "[1,]"
                if ($value == '') {
                    return false;
                }
                return (bool) CastToType::_bool($value);
            case SchemaDefinition::TYPE_TIME:
                $converted = strtotime($value);
                if ($converted === false) {
                    return new Error(
                        'time-cast',
                        sprintf(
                            $this->translationAPI->__('Cannot cast time from \'%s\'', 'component-model'),
                            $value
                        )
                    );
                }
                return $converted;
        }
        return new Error(
            'unidentified-type-cast',
            $this->translationAPI->__('The type of the value cannot be identified, and so cannot be casted', 'component-model')
        );
    }
}
