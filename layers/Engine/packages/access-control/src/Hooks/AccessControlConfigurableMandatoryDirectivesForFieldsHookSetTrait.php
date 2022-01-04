<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\Managers\ComponentManager;
use PoP\AccessControl\Component;
use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait AccessControlConfigurableMandatoryDirectivesForFieldsHookSetTrait
{
    public function maybeFilterFieldName(
        bool $include,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        /**
         * If enabling individual control, then check if there is any entry for this field and schema mode
         */
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->enableIndividualControlForPublicPrivateSchemaMode()) {
            /**
             * If there are no entries, then exit by returning the original hook value
             */
            if (
                empty($this->getEntries(
                    $objectTypeOrInterfaceTypeResolver,
                    $objectTypeOrInterfaceTypeFieldResolver,
                    $fieldName
                ))
            ) {
                return $include;
            }
        }

        /**
         * The parent case deals with the general case
         */
        return parent::maybeFilterFieldName(
            $include,
            $objectTypeOrInterfaceTypeResolver,
            $objectTypeOrInterfaceTypeFieldResolver,
            $fieldName
        );
    }
}
