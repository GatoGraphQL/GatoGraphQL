<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

trait SuggestionsSelectableTypeaheadFormComponentsTrait
{
    protected function enableSuggestions(array $componentVariation)
    {
        return false;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->enableSuggestions($componentVariation)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($componentVariation)) {
                $this->addJsmethod($ret, 'renderDBObjectLayoutFromSuggestion', 'suggestions');
            }
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        if ($this->enableSuggestions($componentVariation)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($componentVariation)) {
                $ret['renderDBObjectLayoutFromSuggestion']['trigger-layout'] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function getExtraTemplateResources(array $componentVariation, array &$props): array
    {
        $ret = parent::getExtraTemplateResources($componentVariation, $props);

        if ($this->enableSuggestions($componentVariation)) {
            if ($this->getSuggestionsLayoutSubmodule($componentVariation)) {
                $ret['extras'] = array(
                    [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS],
                );
            }
        }

        return $ret;
    }

    public function getSuggestionsLayoutSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getSuggestionsFontawesome(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getSuggestionClass(array $componentVariation, array &$props)
    {
        return 'btn btn-link btn-compact';
    }
    public function getSuggestionsTitle(array $componentVariation, array &$props)
    {
        return sprintf(
            '<hr/><div class="suggestions-title"><label>%s</label></div>',
            TranslationAPIFacade::getInstance()->__('Suggestions', 'pop-coreprocessors')
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($this->enableSuggestions($componentVariation)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($componentVariation)) {
                $ret[] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($this->enableSuggestions($componentVariation)) {
            // No suggestions by default. It's overridable from above
            $this->setProp($componentVariation, $props, 'suggestions', array());
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->enableSuggestions($componentVariation)) {
            if ($suggestions = $this->getProp($componentVariation, $props, 'suggestions')) {
                $ret['suggestions'] = $suggestions;

                if ($suggestions_layout = $this->getSuggestionsLayoutSubmodule($componentVariation)) {
                    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['suggestions-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($suggestions_layout);

                    // Load the typeResolver from the trigger, for the suggestions
                    $trigger_layout = $this->getTriggerLayoutSubmodule($componentVariation);
                    /** @var \PoP_Module_Processor_TriggerLayoutFormComponentValuesBase */
                    $triggerComponentProcessor = $componentprocessor_manager->getProcessor($trigger_layout);
                    $suggestions_typeResolver = $triggerComponentProcessor->getTriggerRelationalTypeResolver($trigger_layout);
                    $ret['dbkeys']['suggestions'] = $suggestions_typeResolver->getTypeOutputDBKey();
                }
                if ($suggestions_fontawesome = $this->getSuggestionsFontawesome($componentVariation, $props)) {
                    $ret['suggestions-fontawesome'] = $suggestions_fontawesome;
                }
                if ($suggestion_class = $this->getSuggestionClass($componentVariation, $props)) {
                    $ret[GD_JS_CLASSES]['suggestion'] = $suggestion_class;
                }
                if ($suggestions_title = $this->getSuggestionsTitle($componentVariation, $props)) {
                    $ret[GD_JS_TITLES]['suggestions'] = $suggestions_title;
                }
            }
        }

        return $ret;
    }

    public function getModelSupplementaryDBObjectData(array $componentVariation, array &$props): array
    {

        // Please notice: the IDs to be extended here are permanent, so they can be saved in the configuration for the data-settings
        // If they were not (eg: they are obtained from the URL) then they should be placed under function `getMutableonrequestSupplementaryDbobjectdata` instead
        if ($this->enableSuggestions($componentVariation)) {
            // Pre-loaded suggestions, allowing the user to select the locations easily
            if ($suggestions = $this->getProp($componentVariation, $props, 'suggestions')) {
                if ($trigger_layout = $this->getTriggerLayoutSubmodule($componentVariation)) {
                    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

                    // The Typeahead set the data-settings under 'typeahead-trigger'
                    $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
                    $data_properties = $componentprocessor_manager->getProcessor($trigger_layout)->getDatasetmoduletreeSectionFlattenedDataFields($trigger_layout, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
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

        return parent::getModelSupplementaryDBObjectData($componentVariation, $props);
    }
}
