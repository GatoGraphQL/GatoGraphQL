<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Constants\ConfigurationValues;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Hooks\AbstractAfterAppBootHookSet;

abstract class AbstractAccessControlForFieldsHookSet extends AbstractAfterAppBootHookSet
{
    private ?AccessControlManagerInterface $accessControlManager = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        if ($this->accessControlManager === null) {
            /** @var AccessControlManagerInterface */
            $accessControlManager = $this->instanceManager->getInstance(AccessControlManagerInterface::class);
            $this->accessControlManager = $accessControlManager;
        }
        return $this->accessControlManager;
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

        if ($fieldNames = $this->getFieldNames()) {
            // If "*" defined => it applies to any field
            foreach ($fieldNames as $fieldName) {
                if ($fieldName === ConfigurationValues::ANY) {
                    App::addFilter(
                        HookHelpers::getHookNameToFilterField(),
                        $this->maybeFilterFieldName(...),
                        10,
                        5
                    );
                    continue;
                }
                App::addFilter(
                    HookHelpers::getHookNameToFilterField($fieldName),
                    $this->maybeFilterFieldName(...),
                    10,
                    5
                );
            }
        } else {
            // If no field defined => it applies to any field
            App::addFilter(
                HookHelpers::getHookNameToFilterField(),
                $this->maybeFilterFieldName(...),
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
     *
     * @return string[];
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
