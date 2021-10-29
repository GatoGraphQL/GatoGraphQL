<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Hooks\AbstractCMSBootHookSet;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractAccessControlForFieldsHookSet extends AbstractCMSBootHookSet
{
    private ?AccessControlManagerInterface $accessControlManager = null;

    public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    protected function getAccessControlManager(): AccessControlManagerInterface
    {
        return $this->accessControlManager ??= $this->instanceManager->getInstance(AccessControlManagerInterface::class);
    }

    /**
     * Indicate if this hook is enabled
     */
    protected function enabled(): bool
    {
        return true;
    }
    public function cmsBoot(): void
    {
        if (!$this->enabled()) {
            return;
        }

        // If no field defined => it applies to any field
        if ($fieldNames = $this->getFieldNames()) {
            foreach ($fieldNames as $fieldName) {
                $this->hooksAPI->addFilter(
                    HookHelpers::getHookNameToFilterField($fieldName),
                    array($this, 'maybeFilterFieldName'),
                    10,
                    5
                );
            }
        } else {
            $this->hooksAPI->addFilter(
                HookHelpers::getHookNameToFilterField(),
                array($this, 'maybeFilterFieldName'),
                10,
                5
            );
        }
    }

    public function maybeFilterFieldName(
        bool $include,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        // Because there may be several hooks chained, if any of them has already rejected the field, then already return that response
        if (!$include) {
            return false;
        }

        // Check if to remove the field
        return !$this->removeFieldName(
            $objectTypeOrInterfaceTypeResolver,
            $objectTypeOrInterfaceTypeFieldResolver,
            $fieldName,
        );
    }
    /**
     * Field names to remove
     */
    abstract protected function getFieldNames(): array;
    /**
     * Decide if to remove the fieldNames
     */
    protected function removeFieldName(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        return true;
    }
}
