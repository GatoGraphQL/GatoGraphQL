<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRelationalTypeResolverDecorator implements RelationalTypeResolverDecoratorInterface
{
    use AttachableExtensionTrait;

    protected InstanceManagerInterface $instanceManager;
    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;

    #[Required]
    final public function autowireAbstractRelationalTypeResolverDecorator(InstanceManagerInterface $instanceManager, FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->instanceManager = $instanceManager;
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }

    final public function getClassesToAttachTo(): array
    {
        return $this->getRelationalTypeResolverClassesToAttachTo();
    }

    /**
     * Allow to disable the functionality
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }

    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $objectTypeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     */
    public function getSucceedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }
}
