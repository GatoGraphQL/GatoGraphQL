<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ButtonsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $ret[] = $this->getButtoninnerSubmodule($componentVariation);

        return $ret;
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTON];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = array();
        if ($url = $this->getUrlField($componentVariation)) {
            $ret[] = $url;
        }
        if ($target = $this->getLinktargetField($componentVariation)) {
            $ret[] = $target;
        }

        return $ret;
    }

    public function getUrlField(array $componentVariation)
    {
        return 'url';
    }
    public function getLinktargetField(array $componentVariation)
    {
        return null;
    }
    public function getButtoninnerSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getTitle(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getLinktarget(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getLinkClass(array $componentVariation)
    {
        return '';
    }
    public function getBtnClass(array $componentVariation, array &$props)
    {
        return '';
    }
    public function showTitle(array $componentVariation, array &$props)
    {
        return false;
    }
    public function showTooltip(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->showTooltip($componentVariation, $props)) {
            $this->addJsmethod($ret, 'tooltip');
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->showTitle($componentVariation, $props) || $this->showTooltip($componentVariation, $props)) {
            if ($title = $this->getTitle($componentVariation, $props)) {
                $ret['title'] = $title;
            }
        }

        $ret['url-field'] = $this->getUrlField($componentVariation);
        if ($linktarget = $this->getLinktarget($componentVariation, $props)) {
            $ret['linktarget'] = $linktarget;
        } elseif ($linktarget = $this->getLinktargetField($componentVariation)) {
            $ret['linktarget-field'] = $linktarget;
        }
        $ret[GD_JS_CLASSES]['link'] = $this->getLinkClass($componentVariation);
        if ($btn_class = $this->getProp($componentVariation, $props, 'btn-class')) {
            $ret[GD_JS_CLASSES]['btn'] = $btn_class;
        }

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $buttoninner = $this->getButtoninnerSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['buttoninner'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($buttoninner);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($btn_class = $this->getBtnClass($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'btn-class', $btn_class);
        }
        parent::initModelProps($componentVariation, $props);
    }
}
