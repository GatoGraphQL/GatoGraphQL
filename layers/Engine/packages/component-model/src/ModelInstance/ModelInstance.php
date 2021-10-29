<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModelInstance;

use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ModelInstance implements ModelInstanceInterface
{
    use BasicServiceTrait;

    public const HOOK_COMPONENTS_RESULT = __CLASS__ . ':components:result';
    public const HOOK_COMPONENTSFROMVARS_POSTORGETCHANGE = __CLASS__ . ':componentsFromVars:postOrGetChange';
    public const HOOK_COMPONENTSFROMVARS_RESULT = __CLASS__ . ':componentsFromVars:result';

    private ?ApplicationInfoInterface $applicationInfo = null;
    private ?DefinitionManagerInterface $definitionManager = null;

    public function setApplicationInfo(ApplicationInfoInterface $applicationInfo): void
    {
        $this->applicationInfo = $applicationInfo;
    }
    protected function getApplicationInfo(): ApplicationInfoInterface
    {
        return $this->applicationInfo ??= $this->instanceManager->getInstance(ApplicationInfoInterface::class);
    }
    public function setDefinitionManager(DefinitionManagerInterface $definitionManager): void
    {
        $this->definitionManager = $definitionManager;
    }
    protected function getDefinitionManager(): DefinitionManagerInterface
    {
        return $this->definitionManager ??= $this->instanceManager->getInstance(DefinitionManagerInterface::class);
    }

    public function getModelInstanceId(): string
    {
        // The string is too long. Use a hashing function to shorten it
        return md5(implode('-', $this->getModelInstanceComponents()));
    }

    /**
     * @return string[]
     */
    protected function getModelInstanceComponents(): array
    {
        $components = array();

        // Mix the information specific to the module, with that present in $vars
        $components = (array)$this->getHooksAPI()->applyFilters(
            self::HOOK_COMPONENTS_RESULT,
            array_merge(
                $components,
                $this->getModelInstanceComponentsFromVars()
            )
        );

        // Add the ones from package Definitions

        // Comment Leo 05/04/2017: Also add the module-definition type, for 2 reasons:
        // 1. It allows to create the 2 versions (DEV/PROD) of the configuration files, to compare/debug them side by side
        // 2. It allows to switch from DEV/PROD without having to delete the pop-cache
        if ($definitionResolvers = $this->getDefinitionManager()->getDefinitionResolvers()) {
            $resolvers = [];
            foreach ($definitionResolvers as $group => $resolverInstance) {
                $resolvers[] = $group . '-' . get_class($resolverInstance);
            }
            $components[] = $this->getTranslationAPI()->__('definition resolvers:', 'component-model') . implode(',', $resolvers);
        }

        return $components;
    }

    /**
     * @return string[]
     */
    protected function getModelInstanceComponentsFromVars(): array
    {
        $components = array();

        $vars = ApplicationState::getVars();

        // There will always be a nature. Add it.
        $nature = $vars['nature'];
        $route = $vars['route'];
        $components[] = $this->getTranslationAPI()->__('nature:', 'component-model') . $nature;
        $components[] = $this->getTranslationAPI()->__('route:', 'component-model') . $route;

        // Add the version, because otherwise there may be PHP errors happening from stale configuration that is not deleted, and still served, after a new version is deployed
        $components[] = $this->getTranslationAPI()->__('version:', 'component-model') . $vars['version'];

        // Other properties
        if ($format = $vars['format'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('format:', 'component-model') . $format;
        }
        if ($target = $vars['target'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('target:', 'component-model') . $target;
        }
        if ($actions = $vars['actions'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('actions:', 'component-model') . implode(';', $actions);
        }
        if ($config = $vars['config'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('config:', 'component-model') . $config;
        }
        if ($modulefilter = $vars['modulefilter'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('module filter:', 'component-model') . $modulefilter;
        }

        // Can the configuration change when doing a POST or GET?
        if (
            $this->getHooksAPI()->applyFilters(
                self::HOOK_COMPONENTSFROMVARS_POSTORGETCHANGE,
                false
            )
        ) {
            $components[] = $this->getTranslationAPI()->__('operation:', 'component-model') . ('POST' == $_SERVER['REQUEST_METHOD'] ? 'post' : 'get');
        }
        if ($mangled = $vars['mangled'] ?? null) {
            // By default it is mangled. To make it non-mangled, url must have param "mangled=none",
            // so only in these exceptional cases the identifier will add this parameter
            $components[] = $this->getTranslationAPI()->__('mangled:', 'component-model') . $mangled;
        }
        if ($vars['only-fieldname-as-outputkey'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('only-fieldname-as-outputkey', 'component-model');
        }
        if ($versionConstraint = $vars['version-constraint'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('version-constraint:', 'component-model') . $versionConstraint;
        }
        if ($fieldVersionConstraints = $vars['field-version-constraints'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('field-version-constraints:', 'component-model') . json_encode($fieldVersionConstraints);
        }
        if ($directiveVersionConstraints = $vars['directive-version-constraints'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('directive-version-constraints:', 'component-model') . json_encode($directiveVersionConstraints);
        }

        // Allow for plug-ins to add their own vars. Eg: URE source parameter
        return (array)$this->getHooksAPI()->applyFilters(
            self::HOOK_COMPONENTSFROMVARS_RESULT,
            $components
        );
    }
}
