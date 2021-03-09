<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForFieldsTrait;

trait ConfigurableMandatoryDirectivesForFieldsTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForFieldsTrait;

    public function getClassesToAttachTo(): array
    {
        return array_map(
            function ($entry) {
                // The tuple has format [typeOrFieldInterfaceResolverClass, fieldName]
                // or [typeOrFieldInterfaceResolverClass, fieldName, $role]
                // or [typeOrFieldInterfaceResolverClass, fieldName, $capability]
                // So, in position [0], will always be the $typeOrFieldInterfaceResolverClass
                return $entry[0];
            },
            $this->getConfigurationEntries()
        );
    }

    abstract protected function getMandatoryDirectives($entryValue = null): array;

    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $mandatoryDirectivesForFields = [];
        $fieldInterfaceResolverClasses = $typeResolver->getAllImplementedInterfaceClasses();
        // Obtain all capabilities allowed for the current combination of typeResolver/fieldName
        foreach ($this->getFieldNames() as $fieldName) {
            // Calculate all the interfaces that define this fieldName
            $fieldInterfaceResolverClassesForField = array_values(array_filter(
                $fieldInterfaceResolverClasses,
                function ($fieldInterfaceResolverClass) use ($fieldName, $instanceManager): bool {
                    /** @var FieldInterfaceResolverInterface */
                    $fieldInterfaceResolver = $instanceManager->getInstance($fieldInterfaceResolverClass);
                    return in_array($fieldName, $fieldInterfaceResolver->getFieldNamesToImplement());
                }
            ));
            foreach (
                $this->getEntries(
                    $typeResolver,
                    $fieldInterfaceResolverClassesForField,
                    $fieldName
                ) as $entry
            ) {
                $entryValue = $entry[2] ?? null;
                if ($this->removeFieldNameBasedOnMatchingEntryValue($entryValue)) {
                    $mandatoryDirectivesForFields[$fieldName] = $this->getMandatoryDirectives($entryValue);
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return true;
    }
}
