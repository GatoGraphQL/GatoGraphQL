<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\Root\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string,mixed[]>|null
     */
    protected ?array $schemaDefinition = null;

    private ?SchemaNamespacingServiceInterface $schemaNamespacingService = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    final protected function getSchemaNamespacingService(): SchemaNamespacingServiceInterface
    {
        if ($this->schemaNamespacingService === null) {
            /** @var SchemaNamespacingServiceInterface */
            $schemaNamespacingService = $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
            $this->schemaNamespacingService = $schemaNamespacingService;
        }
        return $this->schemaNamespacingService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        if ($this->schemaDefinitionService === null) {
            /** @var SchemaDefinitionServiceInterface */
            $schemaDefinitionService = $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
            $this->schemaDefinitionService = $schemaDefinitionService;
        }
        return $this->schemaDefinitionService;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        if ($this->attachableExtensionManager === null) {
            /** @var AttachableExtensionManagerInterface */
            $attachableExtensionManager = $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
            $this->attachableExtensionManager = $attachableExtensionManager;
        }
        return $this->attachableExtensionManager;
    }

    public function getNamespace(): string
    {
        return $this->getSchemaNamespacingService()->getSchemaNamespace($this->getClassToNamespace());
    }

    protected function getClassToNamespace(): string
    {
        /** @var string */
        return get_called_class();
    }

    final public function getNamespacedTypeName(): string
    {
        return $this->getSchemaNamespacingService()->getSchemaNamespacedName(
            $this->getNamespace(),
            $this->getTypeName()
        );
    }

    final public function getMaybeNamespacedTypeName(): string
    {
        return App::getState('namespace-types-and-interfaces') ?
            $this->getNamespacedTypeName() :
            $this->getTypeName();
    }

    final public function getTypeOutputKey(): string
    {
        // Do not make the first letter lowercase, or namespaced names look bad
        return $this->getMaybeNamespacedTypeName();
    }

    public function getTypeDescription(): ?string
    {
        return null;
    }

    public function isIntrospectionType(): bool
    {
        return false;
    }
}
