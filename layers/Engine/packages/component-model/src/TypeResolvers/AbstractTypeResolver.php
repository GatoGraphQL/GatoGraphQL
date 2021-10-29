<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\ComponentModel\State\ApplicationState;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string, array>
     */
    protected ?array $schemaDefinition = null;

    private ?SchemaNamespacingServiceInterface $schemaNamespacingService = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    public function setSchemaNamespacingService(SchemaNamespacingServiceInterface $schemaNamespacingService): void
    {
        $this->schemaNamespacingService = $schemaNamespacingService;
    }
    protected function getSchemaNamespacingService(): SchemaNamespacingServiceInterface
    {
        return $this->schemaNamespacingService ??= $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
    }
    public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }

    public function getNamespace(): string
    {
        return $this->getSchemaNamespacingService()->getSchemaNamespace(get_called_class());
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
        $vars = ApplicationState::getVars();
        return $vars['namespace-types-and-interfaces'] ?
            $this->getNamespacedTypeName() :
            $this->getTypeName();
    }

    final public function getTypeOutputDBKey(): string
    {
        // Do not make the first letter lowercase, or namespaced names look bad
        return $this->getMaybeNamespacedTypeName();
    }

    public function getTypeDescription(): ?string
    {
        return null;
    }
}
