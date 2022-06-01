<?php

define('GD_SUBMITFORMTYPE_FETCHBLOCK', 'fetchblock');

abstract class PoP_Module_Processor_FormsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORM];
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
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

    public function getFormType(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_SUBMITFORMTYPE_FETCHBLOCK;
    }

    public function getMethod(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'POST';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
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

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        $ret['action'] = $this->getProp($component, $props, 'action');

        return $ret;
    }

    public function getInterceptType(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($this->getProp($component, $props, 'intercept-action-url')) {
            return 'partialurl';
        }

        return parent::getInterceptType($component, $props);
    }

    public function getAction(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($this->doesComponentLoadData($component)) {
            return $this->getDataloadSource($component, $props);
        }

        return null;
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->setProp($component, $props, 'action', $this->getAction($component, $props));
        parent::initRequestProps($component, $props);
    }
}
