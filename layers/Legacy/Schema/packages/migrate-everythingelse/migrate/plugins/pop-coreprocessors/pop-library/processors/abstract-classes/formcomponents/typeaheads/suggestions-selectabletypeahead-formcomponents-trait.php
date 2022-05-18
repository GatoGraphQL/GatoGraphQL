<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

trait SuggestionsSelectableTypeaheadFormComponentsTrait
{
    protected function enableSuggestions(array $component)
    {
        return false;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($component)) {
                $this->addJsmethod($ret, 'renderDBObjectLayoutFromSuggestion', 'suggestions');
            }
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($component)) {
                $ret['renderDBObjectLayoutFromSuggestion']['trigger-layout'] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function getExtraTemplateResources(array $component, array &$props): array
    {
        $ret = parent::getExtraTemplateResources($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($this->getSuggestionsLayoutSubmodule($component)) {
                $ret['extras'] = array(
                    [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS],
                );
            }
        }

        return $ret;
    }

    public function getSuggestionsLayoutSubmodule(array $component)
    {
        return null;
    }
    public function getSuggestionsFontawesome(array $component, array &$props)
    {
        return null;
    }
    public function getSuggestionClass(array $component, array &$props)
    {
        return 'btn btn-link btn-compact';
    }
    public function getSuggestionsTitle(array $component, array &$props)
    {
        return sprintf(
            '<hr/><div class="suggestions-title"><label>%s</label></div>',
            TranslationAPIFacade::getInstance()->__('Suggestions', 'pop-coreprocessors')
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($this->enableSuggestions($component)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($component)) {
                $ret[] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($this->enableSuggestions($component)) {
            // No suggestions by default. It's overridable from above
            $this->setProp($component, $props, 'suggestions', array());
        }

        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($suggestions = $this->getProp($component, $props, 'suggestions')) {
                $ret['suggestions'] = $suggestions;

                if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($component)) {
                    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['suggestions-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($suggestions_layout);

                    // Load the typeResolver from the trigger, for the suggestions
                    $trigger_layout = $this->getTriggerLayoutSubmodule($component);
                    /** @var \PoP_Module_Processor_TriggerLayoutFormComponentValuesBase */
                    $triggerComponentProcessor = $componentprocessor_manager->getProcessor($trigger_layout);
                    $suggestions_typeResolver = $triggerComponentProcessor->getTriggerRelationalTypeResolver($trigger_layout);
                    $ret['dbkeys']['suggestions'] = $suggestions_typeResolver->getTypeOutputDBKey();
                }
                if ($suggestions_fontawesome = $this->getSuggestionsFontawesome($component, $props)) {
                    $ret['suggestions-fontawesome'] = $suggestions_fontawesome;
                }
                if ($suggestion_class = $this->getSuggestionClass($component, $props)) {
                    $ret[GD_JS_CLASSES]['suggestion'] = $suggestion_class;
                }
                if ($suggestions_title = $this->getSuggestionsTitle($component, $props)) {
                    $ret[GD_JS_TITLES]['suggestions'] = $suggestions_title;
                }
            }
        }

        return $ret;
    }

    public function getModelSupplementaryDBObjectData(array $component, array &$props): array
    {

        // Please notice: the IDs to be extended here are permanent, so they can be saved in the configuration for the data-settings
        // If they were not (eg: they are obtained from the URL) then they should be placed under function `getMutableonrequestSupplementaryDbobjectdata` instead
        if ($this->enableSuggestions($component)) {
            // Pre-loaded suggestions, allowing the user to select the locations easily
            if ($suggestions = $this->getProp($component, $props, 'suggestions')) {
                if ($trigger_layout = $this->getTriggerLayoutSubmodule($component)) {
                    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

                    // The Typeahead set the data-settings under 'typeahead-trigger'
                    $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);
                    $data_properties = $componentprocessor_manager->getProcessor($trigger_layout)->getDatasetmoduletreeSectionFlattenedDataFields($trigger_layout, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]);
                    /** @var \PoP_Module_Processor_TriggerLayoutFormComponentValuesBase */
                    $triggerComponentProcessor = $componentprocessor_manager->getProcessor($trigger_layout);
                    $suggestions_typeResolver = $triggerComponentProcessor->getTriggerRelationalTypeResolver($trigger_layout);

                    // Extend the dataload ids
                    return array(
                        $suggestions_typeResolver->getTypeOutputDBKey() => array(
                            'resolver' => $suggestions_typeResolver,
                            'ids' => $suggestions,
                            'data-fields' => $data_properties['data-fields'],
                        ),
                    );
                }
            }
        }

        return parent::getModelSupplementaryDBObjectData($component, $props);
    }
}
