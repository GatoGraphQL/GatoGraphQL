<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait CheckDangerouslyDynamicScalarFieldOrDirectiveResolverTrait
{
    abstract protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver;

    /**
     * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
     * In particular, it does not need to validate if it is an array or not,
     * as according to the applied WrappingType.
     *
     * If disabled, then do not expose the field if either:
     *
     * 1. its type is `DangerouslyDynamic`
     * 2. it has any mandatory argument of type `DangerouslyDynamic`
     *
     * @param array<string, InputTypeResolverInterface> $consolidatedFieldArgNameTypeResolvers
     * @param array<string, int> $consolidatedFieldArgsTypeModifiers
     */
    protected function isDangerouslyDynamicScalarFieldType(
        TypeResolverInterface $fieldTypeResolver,
        array $consolidatedFieldArgNameTypeResolvers,
        array $consolidatedFieldArgsTypeModifiers,
    ): bool {
        // 1. its type is `DangerouslyDynamic`
        if ($fieldTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()) {
            return true;
        }

        // 2. it has any mandatory argument of type `DangerouslyDynamic`
        if (
            $this->hasMandatoryDangerouslyDynamicScalarInputType(
                $consolidatedFieldArgNameTypeResolvers,
                $consolidatedFieldArgsTypeModifiers,
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param array<string, InputTypeResolverInterface> $consolidatedFieldOrDirectiveArgNameTypeResolvers
     * @param array<string, int> $consolidatedFieldOrDirectiveArgsTypeModifiers
     */
    protected function hasMandatoryDangerouslyDynamicScalarInputType(
        array $consolidatedFieldOrDirectiveArgNameTypeResolvers,
        array $consolidatedFieldOrDirectiveArgsTypeModifiers,
    ): bool {
        $dangerouslyDynamicFieldOrDirectiveArgNameTypeResolvers = array_filter(
            $consolidatedFieldOrDirectiveArgNameTypeResolvers,
            fn (InputTypeResolverInterface $inputTypeResolver) => $inputTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()
        );
        foreach (array_keys($dangerouslyDynamicFieldOrDirectiveArgNameTypeResolvers) as $fieldOrDirectiveArgName) {
            $consolidatedFieldOrDirectiveArgTypeModifiers = $consolidatedFieldOrDirectiveArgsTypeModifiers[$fieldOrDirectiveArgName];
            if ($consolidatedFieldOrDirectiveArgTypeModifiers & SchemaTypeModifiers::MANDATORY) {
                return true;
            }
        }

        return false;
    }
}
