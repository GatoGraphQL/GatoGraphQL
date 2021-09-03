<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected SchemaNamespacingServiceInterface $schemaNamespacingService,
    ) {
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

    public function getTypeOutputName(): string
    {
        // Do not make the first letter lowercase, or namespaced names look bad
        return $this->getMaybeNamespacedTypeName();
    }

    public function getSchemaTypeDescription(): ?string
    {
        return null;
    }
}
