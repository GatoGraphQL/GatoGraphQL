<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Hooks;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

/**
 * To be used together with:
 *
 * - RemoveNodeInterfaceObjectTypeResolverTrait
 * - AbstractTransientObject
 */
abstract class AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookNameToFilterField(),
            $this->filterFields(...),
            10,
            4
        );
    }

    public function filterFields(
        bool $include,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        if (
            ($fieldName !== 'id' && $fieldName !== 'self')
            || ($fieldName === 'self' && !$this->removeSelfField())
        ) {
            return $include;
        }

        $objectTypeOrInterfaceTypeResolverClass = $this->getObjectTypeOrInterfaceTypeResolverClass();
        if (is_a($objectTypeOrInterfaceTypeResolver, $objectTypeOrInterfaceTypeResolverClass, true)) {
            return false;
        }

        return $include;
    }

    protected function removeSelfField(): bool
    {
        return true;
    }

    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    abstract protected function getObjectTypeOrInterfaceTypeResolverClass(): string;
}
