<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ButtonsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $ret[] = $this->getButtoninnerSubmodule($module);

        return $ret;
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTON];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = array();
        if ($url = $this->getUrlField($module)) {
            $ret[] = $url;
        }
        if ($target = $this->getLinktargetField($module)) {
            $ret[] = $target;
        }

        return $ret;
    }

    public function getUrlField(array $module)
    {
        return 'url';
    }
    public function getLinktargetField(array $module)
    {
        return null;
    }
    public function getButtoninnerSubmodule(array $module)
    {
        return null;
    }
    public function getTitle(array $module, array &$props)
    {
        return null;
    }
    public function getLinktarget(array $module, array &$props)
    {
        return null;
    }
    public function getLinkClass(array $module)
    {
        return '';
    }
    public function getBtnClass(array $module, array &$props)
    {
        return '';
    }
    public function showTitle(array $module, array &$props)
    {
        return false;
    }
    public function showTooltip(array $module, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->showTooltip($module, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->showTitle($module, $props) || $this->showTooltip($module, $props)) {
            if ($title = $this->getTitle($module, $props)) {
                $ret['title'] = $title;
            }
        }

        $ret['url-field'] = $this->getUrlField($module);
        if ($linktarget = $this->getLinktarget($module, $props)) {
            $ret['linktarget'] = $linktarget;
        } elseif ($linktarget = $this->getLinktargetField($module)) {
            $ret['linktarget-field'] = $linktarget;
        }
        $ret[GD_JS_CLASSES]['link'] = $this->getLinkClass($module);
        if ($btn_class = $this->getProp($module, $props, 'btn-class')) {
            $ret[GD_JS_CLASSES]['btn'] = $btn_class;
        }

        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $buttoninner = $this->getButtoninnerSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['buttoninner'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($buttoninner);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($btn_class = $this->getBtnClass($module, $props)) {
            $this->appendProp($module, $props, 'btn-class', $btn_class);
        }
        parent::initModelProps($module, $props);
    }
}
