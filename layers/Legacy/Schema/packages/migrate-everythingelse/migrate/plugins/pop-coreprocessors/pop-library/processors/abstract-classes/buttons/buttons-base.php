<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ButtonsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        $ret[] = $this->getButtoninnerSubcomponent($component);

        return $ret;
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTON];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = array();
        if ($url = $this->getUrlField($component)) {
            $ret[] = $url;
        }
        if ($target = $this->getLinktargetField($component)) {
            $ret[] = $target;
        }

        return $ret;
    }

    public function getUrlField(array $component)
    {
        return 'url';
    }
    public function getLinktargetField(array $component)
    {
        return null;
    }
    public function getButtoninnerSubcomponent(array $component)
    {
        return null;
    }
    public function getTitle(array $component, array &$props)
    {
        return null;
    }
    public function getLinktarget(array $component, array &$props)
    {
        return null;
    }
    public function getLinkClass(array $component)
    {
        return '';
    }
    public function getBtnClass(array $component, array &$props)
    {
        return '';
    }
    public function showTitle(array $component, array &$props)
    {
        return false;
    }
    public function showTooltip(array $component, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->showTooltip($component, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->showTitle($component, $props) || $this->showTooltip($component, $props)) {
            if ($title = $this->getTitle($component, $props)) {
                $ret['title'] = $title;
            }
        }

        $ret['url-field'] = $this->getUrlField($component);
        if ($linktarget = $this->getLinktarget($component, $props)) {
            $ret['linktarget'] = $linktarget;
        } elseif ($linktarget = $this->getLinktargetField($component)) {
            $ret['linktarget-field'] = $linktarget;
        }
        $ret[GD_JS_CLASSES]['link'] = $this->getLinkClass($component);
        if ($btn_class = $this->getProp($component, $props, 'btn-class')) {
            $ret[GD_JS_CLASSES]['btn'] = $btn_class;
        }

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $buttoninner = $this->getButtoninnerSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['buttoninner'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getModuleOutputName($buttoninner);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($btn_class = $this->getBtnClass($component, $props)) {
            $this->appendProp($component, $props, 'btn-class', $btn_class);
        }
        parent::initModelProps($component, $props);
    }
}
