<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class PoP_Module_Processor_TriggerLayoutFormComponentValuesBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentValueTrait, FormComponentModuleDelegatorTrait;

    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT];
    }

    public function getFormcomponentComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->getTriggerSubcomponent($component);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // if ($this->getProp($component, $props, 'replicable')) {

        $this->addJsmethod($ret, 'renderDBObjectLayoutFromURLParam');
        // }

        return $ret;
    }

    public function getTriggerRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?RelationalTypeResolverInterface
    {
        return null;
    }
    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        return null;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret[] = $this->getTriggerSubcomponent($component);

        return $ret;
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $trigger_component = $this->getTriggerSubcomponent($component);

        // Add the class to be able to merge
        $this->appendProp($component, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($trigger_component));

        parent::initWebPlatformModelProps($component, $props);
    }

    public function getUrlParam(\PoP\ComponentModel\Component\Component $component)
    {

        // By default, it is the field name. However, it is disassociated: we can pass "references" in the URL to initially set the value,
        // however the form field has a different name, for security (eg: fight spammers)
        return $this->getName($component);
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);

        // Because the URL param and the field name are disassociated, instead of getting ->getValue (which gets the value for the fieldname),
        // we do $_GET instead
        if ($value = \PoP\Root\App::query($this->getUrlParam($component))) {
            $trigger_component = $this->getTriggerSubcomponent($component);
            $this->setProp($trigger_component, $props, 'default-value', $value);
        }

        parent::initRequestProps($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $trigger_component = $this->getTriggerSubcomponent($component);

        // // Because the triggered layout will need to be rendered, it needs to have its template_path printed in the webplatform
        // $this->setProp($trigger_component, $props, 'module-path', true);
        $this->setProp($trigger_component, $props, 'dynamic-component', true);

        // // Initialize typeahead value for replicable/webplatform
        // if ($this->getProp($component, $props, 'replicable')) {

        if ($triggerTypeResolver = $this->getTriggerRelationalTypeResolver($component)) {
            $database_key = $triggerTypeResolver->getTypeOutputDBKey();

            // Needed to execute fillInput on the typeahead input to get the value from the request
            $this->mergeProp(
                $component,
                $props,
                'params',
                array(
                    'data-database-key' => $database_key,
                    'data-urlparam' => $this->getUrlParam($component),
                )
            );
            // }
        }

        parent::initModelProps($component, $props);
    }

    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        // if ($this->getProp($component, $props, 'replicable')) {
        $ret['renderDBObjectLayoutFromURLParam']['trigger-layout'] = $this->getTriggerSubcomponent($component);
        // }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);
        $this->addMetaFormcomponentDataFields($ret, $component, $props);
        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($triggerTypeResolver = $this->getTriggerRelationalTypeResolver($component)) {
            $ret['dbkey'] = $triggerTypeResolver->getTypeOutputDBKey();
        }

        $trigger_component = $this->getTriggerSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['trigger-layout'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($trigger_component);

        $this->addMetaFormcomponentModuleConfiguration($ret, $component, $props);

        return $ret;
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        if ($field = $this->getDbobjectField($component)) {
            return [
                new RelationalComponentFieldNode(
                    new LeafField(
                        $field,
                        null,
                        [],
                        [],
                        LocationHelper::getNonSpecificLocation()
                    ),
                    [
                        $this->getTriggerSubcomponent($component),
                    ]
                ),
            ];
        }

        return parent::getRelationalComponentFieldNodes($component);
    }

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);
        $this->addMetaFormcomponentModuleRuntimeconfiguration($ret, $component, $props);
        return $ret;
    }

    protected function getComponentMutableonrequestSupplementaryDbobjectdataValues(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // Load all the potential values for the Typeahead Trigger:
        // First check for the value. If it has one, that's it
        // If not, in the webplatform it will possibly need 2 other values: 'default-value', and the URL param value
        // But the latter one, only if the URL param is different than the name. If it is not, that case is already covered by ->getValue()
        // $filter = $this->getProp($component, $props, 'filter');
        if ($value = $this->getValue($component)) {
            // If not multiinput, the $value may not be an array
            if (!is_array($value)) {
                $value = array($value);
            }
        } else {
            $value = array();
            if ($default_value = $this->getProp($component, $props, 'default-value')) {
                $value = array_merge(
                    $value,
                    is_array($default_value) ? $default_value : array($default_value)
                );
            }
            if ($this->getUrlParam($component) != $this->getName($component)) {
                if ($urlparam_value = \PoP\Root\App::query($this->getUrlParam($component))) {
                    $value = array_merge(
                        $value,
                        is_array($urlparam_value) ? $urlparam_value : array($urlparam_value)
                    );
                }
            }
        }

        return $value;
    }

    public function getMutableonrequestSupplementaryDbobjectdata(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        if ($value = $this->getComponentMutableonrequestSupplementaryDbobjectdataValues($component, $props)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

            // The Typeahead set the data-settings under 'typeahead-trigger'
            $componentFullName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentFullName($component);
            $trigger_component = $this->getTriggerSubcomponent($component);
            $trigger_data_properties = $componentprocessor_manager->getComponentProcessor($trigger_component)->getDatasetcomponentTreeSectionFlattenedDataFields($trigger_component, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]);

            // Extend the dataload ids
            return array(
                $this->getTriggerRelationalTypeResolver($component)->getTypeName() => array(
                    'ids' => $value,
                    'direct-fields' => $trigger_data_properties['direct-fields'],
                ),
            );
        }

        return parent::getMutableonrequestSupplementaryDbobjectdata($component, $props);
    }
}
