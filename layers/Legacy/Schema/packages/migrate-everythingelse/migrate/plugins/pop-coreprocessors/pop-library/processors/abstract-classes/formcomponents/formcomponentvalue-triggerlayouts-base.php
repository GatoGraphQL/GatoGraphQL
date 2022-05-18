<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class PoP_Module_Processor_TriggerLayoutFormComponentValuesBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentValueTrait, FormComponentModuleDelegatorTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENTVALUE_TRIGGERLAYOUT];
    }

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getTriggerSubmodule($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // if ($this->getProp($componentVariation, $props, 'replicable')) {

        $this->addJsmethod($ret, 'renderDBObjectLayoutFromURLParam');
        // }

        return $ret;
    }

    public function getTriggerRelationalTypeResolver(array $componentVariation): ?RelationalTypeResolverInterface
    {
        return null;
    }
    public function getTriggerSubmodule(array $componentVariation): ?array
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $ret[] = $this->getTriggerSubmodule($componentVariation);

        return $ret;
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        $trigger_componentVariation = $this->getTriggerSubmodule($componentVariation);

        // Add the class to be able to merge
        $this->appendProp($componentVariation, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($trigger_componentVariation));

        parent::initWebPlatformModelProps($componentVariation, $props);
    }

    public function getUrlParam(array $componentVariation)
    {

        // By default, it is the field name. However, it is disassociated: we can pass "references" in the URL to initially set the value,
        // however the form field has a different name, for security (eg: fight spammers)
        return $this->getName($componentVariation);
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);

        // Because the URL param and the field name are disassociated, instead of getting ->getValue (which gets the value for the fieldname),
        // we do $_GET instead
        if ($value = \PoP\Root\App::query($this->getUrlParam($componentVariation))) {
            $trigger_componentVariation = $this->getTriggerSubmodule($componentVariation);
            $this->setProp($trigger_componentVariation, $props, 'default-value', $value);
        }

        parent::initRequestProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $trigger_componentVariation = $this->getTriggerSubmodule($componentVariation);

        // // Because the triggered layout will need to be rendered, it needs to have its template_path printed in the webplatform
        // $this->setProp($trigger_componentVariation, $props, 'module-path', true);
        $this->setProp($trigger_componentVariation, $props, 'dynamic-module', true);

        // // Initialize typeahead value for replicable/webplatform
        // if ($this->getProp($componentVariation, $props, 'replicable')) {

        if ($triggerTypeResolver = $this->getTriggerRelationalTypeResolver($componentVariation)) {
            $database_key = $triggerTypeResolver->getTypeOutputDBKey();

            // Needed to execute fillInput on the typeahead input to get the value from the request
            $this->mergeProp(
                $componentVariation,
                $props,
                'params',
                array(
                    'data-database-key' => $database_key,
                    'data-urlparam' => $this->getUrlParam($componentVariation),
                )
            );
            // }
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        // if ($this->getProp($componentVariation, $props, 'replicable')) {
        $ret['renderDBObjectLayoutFromURLParam']['trigger-layout'] = $this->getTriggerSubmodule($componentVariation);
        // }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
        $this->addMetaFormcomponentDataFields($ret, $componentVariation, $props);
        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($triggerTypeResolver = $this->getTriggerRelationalTypeResolver($componentVariation)) {
            $ret['dbkey'] = $triggerTypeResolver->getTypeOutputDBKey();
        }

        $trigger_componentVariation = $this->getTriggerSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['trigger-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($trigger_componentVariation);

        $this->addMetaFormcomponentModuleConfiguration($ret, $componentVariation, $props);

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        if ($field = $this->getDbobjectField($componentVariation)) {
            return [
                new RelationalModuleField(
                    $field,
                    [
                        $this->getTriggerSubmodule($componentVariation),
                    ]
                ),
            ];
        }

        return parent::getRelationalSubmodules($componentVariation);
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);
        $this->addMetaFormcomponentModuleRuntimeconfiguration($ret, $componentVariation, $props);
        return $ret;
    }

    protected function getModuleMutableonrequestSupplementaryDbobjectdataValues(array $componentVariation, array &$props)
    {

        // Load all the potential values for the Typeahead Trigger:
        // First check for the value. If it has one, that's it
        // If not, in the webplatform it will possibly need 2 other values: 'default-value', and the URL param value
        // But the latter one, only if the URL param is different than the name. If it is not, that case is already covered by ->getValue()
        // $filter = $this->getProp($componentVariation, $props, 'filter');
        if ($value = $this->getValue($componentVariation)) {
            // If not multiinput, the $value may not be an array
            if (!is_array($value)) {
                $value = array($value);
            }
        } else {
            $value = array();
            if ($default_value = $this->getProp($componentVariation, $props, 'default-value')) {
                $value = array_merge(
                    $value,
                    is_array($default_value) ? $default_value : array($default_value)
                );
            }
            if ($this->getUrlParam($componentVariation) != $this->getName($componentVariation)) {
                if ($urlparam_value = \PoP\Root\App::query($this->getUrlParam($componentVariation))) {
                    $value = array_merge(
                        $value,
                        is_array($urlparam_value) ? $urlparam_value : array($urlparam_value)
                    );
                }
            }
        }

        return $value;
    }

    public function getMutableonrequestSupplementaryDbobjectdata(array $componentVariation, array &$props): array
    {
        if ($value = $this->getModuleMutableonrequestSupplementaryDbobjectdataValues($componentVariation, $props)) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

            // The Typeahead set the data-settings under 'typeahead-trigger'
            $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
            $trigger_componentVariation = $this->getTriggerSubmodule($componentVariation);
            $trigger_data_properties = $componentprocessor_manager->getProcessor($trigger_componentVariation)->getDatasetmoduletreeSectionFlattenedDataFields($trigger_componentVariation, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);

            // Extend the dataload ids
            return array(
                $this->getTriggerRelationalTypeResolver($componentVariation)->getTypeName() => array(
                    'ids' => $value,
                    'data-fields' => $trigger_data_properties['data-fields'],
                ),
            );
        }

        return parent::getMutableonrequestSupplementaryDbobjectdata($componentVariation, $props);
    }
}
