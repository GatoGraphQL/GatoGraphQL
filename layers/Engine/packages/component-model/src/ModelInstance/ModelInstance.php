<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModelInstance;

use PoP\Hooks\HooksAPIInterface;
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\State\ApplicationState;

class ModelInstance implements ModelInstanceInterface
{
    public const HOOK_COMPONENTS_RESULT = __CLASS__ . ':components:result';
    public const HOOK_COMPONENTSFROMVARS_POSTORGETCHANGE = __CLASS__ . ':componentsFromVars:postOrGetChange';
    public const HOOK_COMPONENTSFROMVARS_RESULT = __CLASS__ . ':componentsFromVars:result';

    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    protected ApplicationInfoInterface $applicationInfo;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        ApplicationInfoInterface $applicationInfo
    ) {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
        $this->applicationInfo = $applicationInfo;
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
        $components = (array)$this->hooksAPI->applyFilters(
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
        if ($definitionResolvers = DefinitionManagerFacade::getInstance()->getDefinitionResolvers()) {
            $resolvers = [];
            foreach ($definitionResolvers as $group => $resolverInstance) {
                $resolvers[] = $group . '-' . get_class($resolverInstance);
            }
            $components[] = $this->translationAPI->__('definition resolvers:', 'component-model') . implode(',', $resolvers);
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
        $components[] = $this->translationAPI->__('nature:', 'component-model') . $nature;
        $components[] = $this->translationAPI->__('route:', 'component-model') . $route;

        // Add the version, because otherwise there may be PHP errors happening from stale configuration that is not deleted, and still served, after a new version is deployed
        $components[] = $this->translationAPI->__('version:', 'component-model') . $vars['version'];

        // Other properties
        if ($format = $vars['format'] ?? null) {
            $components[] = $this->translationAPI->__('format:', 'component-model') . $format;
        }
        if ($target = $vars['target'] ?? null) {
            $components[] = $this->translationAPI->__('target:', 'component-model') . $target;
        }
        if ($actions = $vars['actions'] ?? null) {
            $components[] = $this->translationAPI->__('actions:', 'component-model') . implode(';', $actions);
        }
        if ($config = $vars['config'] ?? null) {
            $components[] = $this->translationAPI->__('config:', 'component-model') . $config;
        }
        if ($modulefilter = $vars['modulefilter'] ?? null) {
            $components[] = $this->translationAPI->__('module filter:', 'component-model') . $modulefilter;
        }
        if ($stratum = $vars['stratum'] ?? null) {
            $components[] = $this->translationAPI->__('stratum:', 'component-model') . $stratum;
        }

        // Can the configuration change when doing a POST or GET?
        if ($this->hooksAPI->applyFilters(
            self::HOOK_COMPONENTSFROMVARS_POSTORGETCHANGE,
            false
        )) {
            $components[] = $this->translationAPI->__('operation:', 'component-model') . (doingPost() ? 'post' : 'get');
        }
        if ($mangled = $vars['mangled'] ?? null) {
            // By default it is mangled. To make it non-mangled, url must have param "mangled=none",
            // so only in these exceptional cases the identifier will add this parameter
            $components[] = $this->translationAPI->__('mangled:', 'component-model') . $mangled;
        }
        if ($vars['only-fieldname-as-outputkey'] ?? null) {
            $components[] = $this->translationAPI->__('only-fieldname-as-outputkey', 'component-model');
        }
        if ($versionConstraint = $vars['version-constraint'] ?? null) {
            $components[] = $this->translationAPI->__('version-constraint:', 'component-model') . $versionConstraint;
        }
        if ($fieldVersionConstraints = $vars['field-version-constraints'] ?? null) {
            $components[] = $this->translationAPI->__('field-version-constraints:', 'component-model') . json_encode($fieldVersionConstraints);
        }
        if ($directiveVersionConstraints = $vars['directive-version-constraints'] ?? null) {
            $components[] = $this->translationAPI->__('directive-version-constraints:', 'component-model') . json_encode($directiveVersionConstraints);
        }

        // Allow for plug-ins to add their own vars. Eg: URE source parameter
        return (array)$this->hooksAPI->applyFilters(
            self::HOOK_COMPONENTSFROMVARS_RESULT,
            $components
        );
    }
}
