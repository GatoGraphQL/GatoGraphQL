<?php

define('GD_SUBMITFORMTYPE_FETCHBLOCK', 'fetchblock');

abstract class PoP_Module_Processor_FormsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORM];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // $this->addJsmethod($ret, 'formHandleDisabledLayer');

        $form_type = $this->getFormType($componentVariation, $props);
        if ($form_type == GD_SUBMITFORMTYPE_FETCHBLOCK) {
            $this->addJsmethod($ret, 'forms');
        }

        if ($this->getProp($componentVariation, $props, 'intercept-action-url')) {
            $this->addJsmethod($ret, 'interceptForm', 'interceptor');
        }
        return $ret;
    }

    public function getFormType(array $componentVariation, array &$props)
    {
        return GD_SUBMITFORMTYPE_FETCHBLOCK;
    }

    public function getMethod(array $componentVariation, array &$props)
    {
        return 'POST';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['method'] = $this->getMethod($componentVariation, $props);
        if ($description = $this->getProp($componentVariation, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }
        if ($description_bottom = $this->getProp($componentVariation, $props, 'description-bottom')) {
            $ret['description-bottom'] = $description_bottom;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        $ret['action'] = $this->getProp($componentVariation, $props, 'action');

        return $ret;
    }

    public function getInterceptType(array $componentVariation, array &$props)
    {
        if ($this->getProp($componentVariation, $props, 'intercept-action-url')) {
            return 'partialurl';
        }

        return parent::getInterceptType($componentVariation, $props);
    }

    public function getAction(array $componentVariation, array &$props)
    {
        if ($this->moduleLoadsData($componentVariation)) {
            return $this->getDataloadSource($componentVariation, $props);
        }

        return null;
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'action', $this->getAction($componentVariation, $props));
        parent::initRequestProps($componentVariation, $props);
    }
}
