<?php

declare(strict_types=1);

namespace PoPWPSchema\SchemaCommons\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::getHookManager()->addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            [$this, 'getInputFieldTypeModifiers'],
            10,
            3
        );
    }

    abstract protected function isInputObjectTypeResolver(InputObjectTypeResolverInterface $inputObjectTypeResolver): bool;

    /**
     * Transform "dateQuery" from a single value to an array of them
     */
    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'dateQuery' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => $inputFieldTypeModifiers,
        };
    }
}
