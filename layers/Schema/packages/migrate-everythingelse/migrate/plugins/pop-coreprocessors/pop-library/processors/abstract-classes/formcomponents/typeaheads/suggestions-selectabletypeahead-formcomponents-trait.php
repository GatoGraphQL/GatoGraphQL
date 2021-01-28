<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

trait SuggestionsSelectableTypeaheadFormComponentsTrait
{
    protected function enableSuggestions(array $module)
    {
        return false;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->enableSuggestions($module)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($module)) {
                $this->addJsmethod($ret, 'renderDBObjectLayoutFromSuggestion', 'suggestions');
            }
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        if ($this->enableSuggestions($module)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($module)) {
                $ret['renderDBObjectLayoutFromSuggestion']['trigger-layout'] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function getExtraTemplateResources(array $module, array &$props): array
    {
        $ret = parent::getExtraTemplateResources($module, $props);

        if ($this->enableSuggestions($module)) {
            if ($this->getSuggestionsLayoutSubmodule($module)) {
                $ret['extras'] = array(
                    [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS],
                );
            }
        }

        return $ret;
    }

    public function getSuggestionsLayoutSubmodule(array $module)
    {
        return null;
    }
    public function getSuggestionsFontawesome(array $module, array &$props)
    {
        return null;
    }
    public function getSuggestionClass(array $module, array &$props)
    {
        return 'btn btn-link btn-compact';
    }
    public function getSuggestionsTitle(array $module, array &$props)
    {
        return sprintf(
            '<hr/><div class="suggestions-title"><label>%s</label></div>',
            TranslationAPIFacade::getInstance()->__('Suggestions', 'pop-coreprocessors')
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($this->enableSuggestions($module)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($module)) {
                $ret[] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        if ($this->enableSuggestions($module)) {
            // No suggestions by default. It's overridable from above
            $this->setProp($module, $props, 'suggestions', array());
        }

        parent::initModelProps($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->enableSuggestions($module)) {
            if ($suggestions = $this->getProp($module, $props, 'suggestions')) {
                $ret['suggestions'] = $suggestions;

                if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($module)) {
                    $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['suggestions-layout'] = ModuleUtils::getModuleOutputName($suggestions_layout);

                    // Load the typeResolver from the trigger, for the suggestions
                    $instanceManager = InstanceManagerFacade::getInstance();
                    $trigger_layout = $this->getTriggerLayoutSubmodule($module);
                    $suggestions_typeResolver_class = $moduleprocessor_manager->getProcessor($trigger_layout)->getTriggerTypeResolverClass($trigger_layout);
                    $suggestions_typeResolver = $instanceManager->getInstance($suggestions_typeResolver_class);
                    $ret['dbkeys']['suggestions'] = $suggestions_typeResolver->getTypeOutputName();
                }
                if ($suggestions_fontawesome = $this->getSuggestionsFontawesome($module, $props)) {
                    $ret['suggestions-fontawesome'] = $suggestions_fontawesome;
                }
                if ($suggestion_class = $this->getSuggestionClass($module, $props)) {
                    $ret[GD_JS_CLASSES]['suggestion'] = $suggestion_class;
                }
                if ($suggestions_title = $this->getSuggestionsTitle($module, $props)) {
                    $ret[GD_JS_TITLES]['suggestions'] = $suggestions_title;
                }
            }
        }

        return $ret;
    }

    public function getModelSupplementaryDbobjectdata(array $module, array &$props): array
    {

        // Please notice: the IDs to be extended here are permanent, so they can be saved in the configuration for the data-settings
        // If they were not (eg: they are obtained from the URL) then they should be placed under function `getMutableonrequestSupplementaryDbobjectdata` instead
        if ($this->enableSuggestions($module)) {
            // Pre-loaded suggestions, allowing the user to select the locations easily
            if ($suggestions = $this->getProp($module, $props, 'suggestions')) {
                if ($trigger_layout = $this->getTriggerLayoutSubmodule($module)) {
                    $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

                    // The Typeahead set the data-settings under 'typeahead-trigger'
                    $moduleFullName = ModuleUtils::getModuleFullName($module);
                    $data_properties = $moduleprocessor_manager->getProcessor($trigger_layout)->getDatasetmoduletreeSectionFlattenedDataFields($trigger_layout, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
                    $suggestions_typeResolver_class = $moduleprocessor_manager->getProcessor($trigger_layout)->getTriggerTypeResolverClass($trigger_layout);

                    // Extend the dataload ids
                    return array(
                        $suggestions_typeResolver_class => array(
                            'ids' => $suggestions,
                            'data-fields' => $data_properties['data-fields'],
                        ),
                    );
                }
            }
        }

        return parent::getModelSupplementaryDbobjectdata($module, $props);
    }
}
