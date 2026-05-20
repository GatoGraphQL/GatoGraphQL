<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\Root\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Root\Services\AbstractBasicService;

abstract class AbstractTypeResolver extends AbstractBasicService implements TypeResolverInterface
{
    /**
     * @var array<string,mixed[]>|null
     */
    protected ?array $schemaDefinition = null;

    private ?SchemaNamespacingServiceInterface $schemaNamespacingService = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    /**
     * Memoized result of `getNamespacedTypeName()`. The translation
     * profile shows it at 280M / 140K calls — `final` and depends only
     * on `$this`'s class (the type-name and namespace are derived from
     * `get_called_class` plus configuration that's stable for the
     * lifetime of the type resolver instance).
     *
     * Not memoized: `getMaybeNamespacedTypeName()`. It branches on
     * `App::getState('namespace-types-and-interfaces')` whose value
     * can differ between test runs (and in principle between requests
     * in long-running PHP processes); a cache there leaks across tests.
     */
    private ?string $namespacedTypeName = null;

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
        return get_called_class();
    }

    final public function getNamespacedTypeName(): string
    {
        return $this->namespacedTypeName ??= $this->getSchemaNamespacingService()->getSchemaNamespacedName(
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
