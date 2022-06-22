<?php
use PoP\ComponentModel\Constants\DataProperties;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

trait SuggestionsSelectableTypeaheadFormComponentsTrait
{
    protected function enableSuggestions(\PoP\ComponentModel\Component\Component $component)
    {
        return false;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubcomponent($component)) {
                $this->addJsmethod($ret, 'renderDBObjectLayoutFromSuggestion', 'suggestions');
            }
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubcomponent($component)) {
                $ret['renderDBObjectLayoutFromSuggestion']['trigger-layout'] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function getExtraTemplateResources(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getExtraTemplateResources($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($this->getSuggestionsLayoutSubcomponent($component)) {
                $ret['extras'] = array(
                    [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONTYPEAHEADSUGGESTIONS],
                );
            }
        }

        return $ret;
    }

    public function getSuggestionsLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getSuggestionsFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function getSuggestionClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'btn btn-link btn-compact';
    }
    public function getSuggestionsTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return sprintf(
            '<hr/><div class="suggestions-title"><label>%s</label></div>',
            TranslationAPIFacade::getInstance()->__('Suggestions', 'pop-coreprocessors')
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($this->enableSuggestions($component)) {
            if ($suggestions_layout = $this->getSuggestionsLayoutSubcomponent($component)) {
                $ret[] = $suggestions_layout;
            }
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($this->enableSuggestions($component)) {
            // No suggestions by default. It's overridable from above
            $this->setProp($component, $props, 'suggestions', array());
        }

        parent::initModelProps($component, $props);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->enableSuggestions($component)) {
            if ($suggestions = $this->getProp($component, $props, 'suggestions')) {
                $ret['suggestions'] = $suggestions;

                if ($suggestions_layout = $this->getSuggestionsLayoutSubcomponent($component)) {
                    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

                    $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['suggestions-layout'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($suggestions_layout);

                    // Load the typeResolver from the trigger, for the suggestions
                    $trigger_layout = $this->getTriggerLayoutSubcomponent($component);
                    /** @var \PoP_Module_Processor_TriggerLayoutFormComponentValuesBase */
                    $triggerComponentProcessor = $componentprocessor_manager->getComponentProcessor($trigger_layout);
                    $suggestions_typeResolver = $triggerComponentProcessor->getTriggerRelationalTypeResolver($trigger_layout);
                    $ret['outputKeys']['suggestions'] = $suggestions_typeResolver->getTypeOutputKey();
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

    public function getModelSupplementaryDBObjectData(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {

        // Please notice: the IDs to be extended here are permanent, so they can be saved in the configuration for the data-settings
        // If they were not (eg: they are obtained from the URL) then they should be placed under function `getMutableonrequestSupplementaryDbobjectdata` instead
        if ($this->enableSuggestions($component)) {
            // Pre-loaded suggestions, allowing the user to select the locations easily
            if ($suggestions = $this->getProp($component, $props, 'suggestions')) {
                if ($trigger_layout = $this->getTriggerLayoutSubcomponent($component)) {
                    $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

                    // The Typeahead set the data-settings under 'typeahead-trigger'
                    $componentFullName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentFullName($component);
                    $data_properties = $componentprocessor_manager->getComponentProcessor($trigger_layout)->getDatasetComponentTreeSectionFlattenedDataProperties($trigger_layout, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]);
                    /** @var \PoP_Module_Processor_TriggerLayoutFormComponentValuesBase */
                    $triggerComponentProcessor = $componentprocessor_manager->getComponentProcessor($trigger_layout);
                    $suggestions_typeResolver = $triggerComponentProcessor->getTriggerRelationalTypeResolver($trigger_layout);

                    // Extend the dataload ids
                    return array(
                        $suggestions_typeResolver->getTypeOutputKey() => array(
                            DataProperties::RESOLVER => $suggestions_typeResolver,
                            DataProperties::IDS => $suggestions,
                            DataProperties::DIRECT_COMPONENT_FIELD_NODES => $data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES],
                        ),
                    );
                }
            }
        }

        return parent::getModelSupplementaryDBObjectData($component, $props);
    }
}
