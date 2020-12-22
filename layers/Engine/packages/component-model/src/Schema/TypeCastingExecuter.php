<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\TranslationAPIInterface;
use CastToType;
use DateTime;

class TypeCastingExecuter implements TypeCastingExecuterInterface
{
    private TranslationAPIInterface $translationAPI;

    public function __construct(
        TranslationAPIInterface $translationAPI
    ) {
        $this->translationAPI = $translationAPI;
    }

    /**
     * Cast the value to the indicated type, or return null or Error (with a message) if it fails
     *
     * @param string $type
     * @param string $value
     * @return void
     */
    public function cast(string $type, $value)
    {
        switch ($type) {
            case SchemaDefinition::TYPE_MIXED:
                // Accept anything and everything
                return $value;
            case SchemaDefinition::TYPE_ID:
                // An array or an object cannot be an ID
                if (is_array($value) || is_object($value)) {
                    return null;
                }
                // Accept anything and everything
                return $value;
            case SchemaDefinition::TYPE_STRING:
                return (string)$value;
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
                    return null;
                }
                return $value;
            case SchemaDefinition::TYPE_ENUM:
                // It is not possible to validate this, so just return whatever it gets
                return $value;
            case SchemaDefinition::TYPE_ARRAY:
                if (!is_array($value)) {
                    return null;
                }
                return $value;
            case SchemaDefinition::TYPE_OBJECT:
            case SchemaDefinition::TYPE_INPUT_OBJECT:
                if (!is_array($value) && !is_object($value)) {
                    return null;
                }
                return $value;
            case SchemaDefinition::TYPE_DATE:
                if (!is_string($value)) {
                    return new Error(
                        'date-cast',
                        $this->translationAPI->__('Date must be provided as a string')
                    );
                }
                // Validate that the format is 'Y-m-d'
                // Taken from https://stackoverflow.com/a/13194398
                $dt = DateTime::createFromFormat("Y-m-d", $value);
                if ($dt === false || array_sum($dt::getLastErrors())) {
                    return new Error(
                        'date-cast',
                        sprintf(
                            $this->translationAPI->__('Date format must be \'%s\''),
                            'Y-m-d'
                        )
                    );
                }
                return $value;
            case SchemaDefinition::TYPE_INT:
                return CastToType::_int($value);
            case SchemaDefinition::TYPE_FLOAT:
                return CastToType::_float($value);
            case SchemaDefinition::TYPE_BOOL:
                // Watch out! In Library CastToType, an empty string is not false, but it's NULL
                // But for us it must be false, since calling query ?query=and([true,false]) gets transformed to the $field string "[1,]"
                if ($value == '') {
                    return false;
                }
                return CastToType::_bool($value);
            case SchemaDefinition::TYPE_TIME:
                $converted = strtotime($value);
                if ($converted === false) {
                    return null;
                }
                return $converted;
        }
        return $value;
    }
}
