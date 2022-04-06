<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\ExcludeFieldNamesFromSchemaTypeResolverTrait;

abstract class AbstractInterfaceTypeResolver extends AbstractTypeResolver implements InterfaceTypeResolverInterface
{
    use ExcludeFieldNamesFromSchemaTypeResolverTrait;

    /**
     * @var array<string, InterfaceTypeFieldResolverInterface[]>|null
     */
    protected ?array $interfaceTypeFieldResolversByField = null;
    /**
     * @var array<string, InterfaceTypeFieldResolverInterface>|null
     */
    protected ?array $excutableInterfaceTypeFieldResolversByField = null;
    /**
     * @var string[]|null
     */
    protected ?array $fieldNamesToImplement = null;
    /**
     * @var array<string, array>
     */
    private array $fieldNamesResolvedByInterfaceTypeFieldResolver = [];
    /**
     * @var InterfaceTypeFieldResolverInterface[]|null
     */
    protected ?array $interfaceTypeFieldResolvers = null;

    /**
     * The list of the fieldNames to implement in the Interface,
     * collected from all the injected InterfaceTypeFieldResolvers
     *
     * @return string[]
     */
    final public function getFieldNamesToImplement(): array
    {
        if ($this->fieldNamesToImplement === null) {
            $this->fieldNamesToImplement = $this->calculateFieldNamesToImplement();
        }
        return $this->fieldNamesToImplement;
    }

    private function calculateFieldNamesToImplement(): array
    {
        $fieldNamesToImplement = [];
        foreach ($this->getInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            $fieldNamesToImplement = array_merge(
                $fieldNamesToImplement,
                $interfaceTypeFieldResolver->getFieldNamesToImplement()
            );
        }
        return array_values(array_unique($fieldNamesToImplement));
    }

    /**
     * Interfaces "partially" implemented by this Interface
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public function getPartiallyImplementedInterfaceTypeResolvers(): array
    {
        $implementedInterfaceTypeFieldResolvers = [];
        foreach ($this->getInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            // Add under class as to mimick `array_unique` for object
            foreach ($interfaceTypeFieldResolver->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
                $implementedInterfaceTypeFieldResolvers[get_class($implementedInterfaceTypeFieldResolver)] = $implementedInterfaceTypeFieldResolver;
            }
        }
        $implementedInterfaceTypeResolvers = [];
        foreach ($implementedInterfaceTypeFieldResolvers as $implementedInterfaceTypeFieldResolver) {
            // Add under class as to mimick `array_unique` for object
            foreach ($implementedInterfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers() as $partiallyImplementedInterfaceTypeResolver) {
                $implementedInterfaceTypeResolvers[get_class($partiallyImplementedInterfaceTypeResolver)] = $partiallyImplementedInterfaceTypeResolver;
            }
        }
        return array_values($implementedInterfaceTypeResolvers);
    }

    /**
     * Produce an array of all the attached ObjectTypeFieldResolverInterfaces
     *
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getInterfaceTypeFieldResolvers(): array
    {
        if ($this->interfaceTypeFieldResolvers === null) {
            $interfaceTypeFieldResolvers = [];
            foreach ($this->getInterfaceTypeFieldResolversByField() as $fieldName => $interfaceTypeFieldResolversByField) {
                // Add under class as to mimick `array_unique` for object
                foreach ($interfaceTypeFieldResolversByField as $interfaceTypeFieldResolver) {
                    $interfaceTypeFieldResolvers[get_class($interfaceTypeFieldResolver)] = $interfaceTypeFieldResolver;
                }
            }
            $this->interfaceTypeFieldResolvers = array_values($interfaceTypeFieldResolvers);
        }
        return $this->interfaceTypeFieldResolvers;
    }

    final public function getExecutableInterfaceTypeFieldResolversByField(): array
    {
        if ($this->excutableInterfaceTypeFieldResolversByField === null) {
            $this->excutableInterfaceTypeFieldResolversByField = $this->doGetExecutableInterfaceTypeFieldResolversByField();
        }
        return $this->excutableInterfaceTypeFieldResolversByField;
    }

    private function doGetExecutableInterfaceTypeFieldResolversByField(): array
    {
        $interfaceTypeFieldResolvers = [];
        foreach ($this->getInterfaceTypeFieldResolversByField() as $fieldName => $fieldInterfaceTypeFieldResolvers) {
            // Get the first item from the list of resolvers. That's the one that will be executed
            $interfaceTypeFieldResolvers[$fieldName] = $fieldInterfaceTypeFieldResolvers[0];
        }
        return $interfaceTypeFieldResolvers;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the ObjectTypeFieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    final public function getInterfaceTypeFieldResolversByField(): array
    {
        if ($this->interfaceTypeFieldResolversByField === null) {
            $this->interfaceTypeFieldResolversByField = $this->calculateAllInterfaceTypeFieldResolversByField();
        }
        return $this->interfaceTypeFieldResolversByField;
    }

    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the ObjectTypeFieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    protected function calculateAllInterfaceTypeFieldResolversByField(): array
    {
        $interfaceTypeFieldResolversByField = [];

        // Get the InterfaceTypeFieldResolvers attached to this InterfaceTypeResolver
        // and to all the interfaces it implements
        $classStack = [
            get_called_class(),
        ];
        while (!empty($classStack)) {
            $class = array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                /** @var InterfaceTypeFieldResolverInterface[] */
                $attachedInterfaceTypeFieldResolvers = $this->getAttachableExtensionManager()->getAttachedExtensions($class, AttachableExtensionGroups::INTERFACE_TYPE_FIELD_RESOLVERS);
                foreach ($attachedInterfaceTypeFieldResolvers as $interfaceTypeFieldResolver) {
                    // Process the fields which have not been processed yet
                    $extensionFieldNames = $this->getFieldNamesResolvedByInterfaceTypeFieldResolver($interfaceTypeFieldResolver);
                    foreach (array_diff($extensionFieldNames, array_keys($interfaceTypeFieldResolversByField)) as $fieldName) {
                        $interfaceTypeFieldResolversByField[$fieldName] ??= [];
                        $interfaceTypeFieldResolversByField[$fieldName][] = $interfaceTypeFieldResolver;
                    }
                    // The interfaces implemented by the InterfaceTypeFieldResolver can have, themselves, InterfaceTypeFieldResolvers attached to them
                    $classStack = array_values(array_unique(array_merge(
                        $classStack,
                        array_map(
                            get_class(...),
                            $interfaceTypeFieldResolver->getImplementedInterfaceTypeFieldResolvers()
                        ),
                    )));
                }
                // Otherwise, continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        return $interfaceTypeFieldResolversByField;
    }

    /**
     * Return the fieldNames resolved by the interfaceTypeFieldResolver, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @return string[]
     */
    protected function getFieldNamesResolvedByInterfaceTypeFieldResolver(InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver): array
    {
        $interfaceTypeFieldResolverClass = get_class($interfaceTypeFieldResolver);
        if (!isset($this->fieldNamesResolvedByInterfaceTypeFieldResolver[$interfaceTypeFieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = $interfaceTypeFieldResolver->getFieldNamesToImplement();
            $fieldNames = $this->maybeExcludeFieldNamesFromSchema(
                $this,
                $interfaceTypeFieldResolver,
                $fieldNames
            );
            $this->fieldNamesResolvedByInterfaceTypeFieldResolver[$interfaceTypeFieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByInterfaceTypeFieldResolver[$interfaceTypeFieldResolverClass];
    }
}
