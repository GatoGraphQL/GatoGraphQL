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
 * To be used together with RemoveNodeInterfaceObjectTypeResolverTrait
 */
abstract class AbstractRemoveIDFieldFromObjectTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookNameToFilterField(),
            $this->filterIDField(...),
            10,
            4
        );
    }

    public function filterIDField(
        bool $include,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        if ($fieldName !== 'id') {
            return $include;
        }
        $objectTypeOrInterfaceTypeResolverClass = $this->getObjectTypeOrInterfaceTypeResolverClass();
        if (is_a($objectTypeOrInterfaceTypeResolver, $objectTypeOrInterfaceTypeResolverClass, true)) {
            return false;
        }

        return $include;
    }

    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    abstract protected function getObjectTypeOrInterfaceTypeResolverClass(): string;
}
