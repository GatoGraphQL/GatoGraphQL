<?php

define('GD_SUBMITFORMTYPE_FETCHBLOCK', 'fetchblock');

abstract class PoP_Module_Processor_FormsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORM];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // $this->addJsmethod($ret, 'formHandleDisabledLayer');

        $form_type = $this->getFormType($component, $props);
        if ($form_type == GD_SUBMITFORMTYPE_FETCHBLOCK) {
            $this->addJsmethod($ret, 'forms');
        }

        if ($this->getProp($component, $props, 'intercept-action-url')) {
            $this->addJsmethod($ret, 'interceptForm', 'interceptor');
        }
        return $ret;
    }

    public function getFormType(array $component, array &$props)
    {
        return GD_SUBMITFORMTYPE_FETCHBLOCK;
    }

    public function getMethod(array $component, array &$props)
    {
        return 'POST';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['method'] = $this->getMethod($component, $props);
        if ($description = $this->getProp($component, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }
        if ($description_bottom = $this->getProp($component, $props, 'description-bottom')) {
            $ret['description-bottom'] = $description_bottom;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        $ret['action'] = $this->getProp($component, $props, 'action');

        return $ret;
    }

    public function getInterceptType(array $component, array &$props)
    {
        if ($this->getProp($component, $props, 'intercept-action-url')) {
            return 'partialurl';
        }

        return parent::getInterceptType($component, $props);
    }

    public function getAction(array $component, array &$props)
    {
        if ($this->moduleLoadsData($component)) {
            return $this->getDataloadSource($component, $props);
        }

        return null;
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'action', $this->getAction($component, $props));
        parent::initRequestProps($component, $props);
    }
}
