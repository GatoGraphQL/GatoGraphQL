<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    /**
     * @var array<string, array>
     */
    protected ?array $schemaDefinition = null;
    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    protected InstanceManagerInterface $instanceManager;
    protected SchemaNamespacingServiceInterface $schemaNamespacingService;
    protected SchemaDefinitionServiceInterface $schemaDefinitionService;
    protected AttachableExtensionManagerInterface $attachableExtensionManager;

    #[Required]
    final public function autowireAbstractTypeResolver(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, SchemaNamespacingServiceInterface $schemaNamespacingService, SchemaDefinitionServiceInterface $schemaDefinitionService, AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
        $this->schemaNamespacingService = $schemaNamespacingService;
        $this->schemaDefinitionService = $schemaDefinitionService;
        $this->attachableExtensionManager = $attachableExtensionManager;
    }

    public function getNamespace(): string
    {
        return $this->schemaNamespacingService->getSchemaNamespace(get_called_class());
    }

    final public function getNamespacedTypeName(): string
    {
        return $this->schemaNamespacingService->getSchemaNamespacedName(
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
