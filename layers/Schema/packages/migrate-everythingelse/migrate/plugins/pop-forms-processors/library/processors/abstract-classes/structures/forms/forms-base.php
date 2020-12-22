<?php

define('GD_SUBMITFORMTYPE_FETCHBLOCK', 'fetchblock');

abstract class PoP_Module_Processor_FormsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORM];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // $this->addJsmethod($ret, 'formHandleDisabledLayer');
        
        $form_type = $this->getFormType($module, $props);
        if ($form_type == GD_SUBMITFORMTYPE_FETCHBLOCK) {
            $this->addJsmethod($ret, 'forms');
        }
        
        if ($this->getProp($module, $props, 'intercept-action-url')) {
            $this->addJsmethod($ret, 'interceptForm', 'interceptor');
        }
        return $ret;
    }

    public function getFormType(array $module, array &$props)
    {
        return GD_SUBMITFORMTYPE_FETCHBLOCK;
    }

    public function getMethod(array $module, array &$props)
    {
        return 'POST';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['method'] = $this->getMethod($module, $props);
        if ($description = $this->getProp($module, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }
        if ($description_bottom = $this->getProp($module, $props, 'description-bottom')) {
            $ret['description-bottom'] = $description_bottom;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        $ret['action'] = $this->getProp($module, $props, 'action');

        return $ret;
    }

    public function getInterceptType(array $module, array &$props)
    {
        if ($this->getProp($module, $props, 'intercept-action-url')) {
            return 'partialurl';
        }

        return parent::getInterceptType($module, $props);
    }

    public function getAction(array $module, array &$props)
    {
        if ($this->moduleLoadsData($module)) {
            return $this->getDataloadSource($module, $props);
        }

        return null;
    }

    public function initRequestProps(array $module, array &$props)
    {
        $this->setProp($module, $props, 'action', $this->getAction($module, $props));
        parent::initRequestProps($module, $props);
    }
}
