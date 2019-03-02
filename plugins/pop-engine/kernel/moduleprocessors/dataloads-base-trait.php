<?php
namespace PoP\Engine;

trait DataloadModuleProcessorBaseTrait
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getDataloadingModule($module)
    {
        return $module;
    }

    public function getFormat($module)
    {
        return null;
    }

    public function getFilterModule($module)
    {
        return null;
    }

    public function getFilter($module)
    {
        if ($filter_module = $this->getFilterModule($module)) {
            $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
            return $moduleprocessor_manager->getProcessor($filter_module)->getFilter($filter_module);
        }

        return parent::getFilter($module);
    }
    
    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    public function getModules($module)
    {
        $ret = parent::getModules($module);

        if ($filter = $this->getFilterModule($module)) {
            $ret[] = $filter;
        }

        if ($inners = $this->getInnerModules($module)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }
                
        return $ret;
    }

    protected function getInnerModules($module)
    {
        return array();
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function metaInitProps($module, &$props)
    {
        /**
         * Allow to add more stuff
         */
        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction(
            '\PoP\Engine\DataloadModuleProcessorBaseTrait:initModelProps',
            array(&$props),
            $module,
            $this
        );
    }

    public function getModelPropsForDescendantDatasetmodules($module, &$props)
    {
        $ret = parent::getModelPropsForDescendantDatasetmodules($module, $props);

        if ($filter_module = $this->getFilterModule($module)) {
            $ret['filter-module'] = $filter_module;
        }
        if ($filter = $this->getFilter($module)) {
            $ret['filter'] = $filter;
        }

        return $ret;
    }

    public function initModelProps($module, &$props)
    {
        $this->metaInitProps($module, $props);
        parent::initModelProps($module, $props);
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function getDataloader($module)
    {
        return GD_DATALOADER_NIL;
    }
}
