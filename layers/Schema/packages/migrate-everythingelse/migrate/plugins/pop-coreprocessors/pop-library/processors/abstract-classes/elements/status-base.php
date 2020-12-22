<?php

abstract class PoP_Module_Processor_StatusBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_STATUS];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
        
        // Error: allow for status-specific message, or a general one
        $ret[GD_JS_TITLES]['error'] = sprintf(
            // status = 0 => User is offline
            '<span class="errormsg status-0 hidden">%s</span>',
            GD_CONSTANT_OFFLINE_MSG
        ).sprintf(
            // General message
            '<span class="errormsg general hidden">%s</span>',
            GD_CONSTANT_ERROR_MSG
        );
        $loading = sprintf(
            '%s %s',
            $this->getProp($module, $props, 'loading-spinner'),
            $this->getProp($module, $props, 'loading-msg')
        );
        $ret[GD_JS_TITLES]['loading'] = $loading;
        $ret[GD_JS_TITLES]['retry'] = GD_CONSTANT_RETRY_MSG;

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        
        $this->addJsmethod($ret, 'switchTargetClass', 'error-dismiss');
        $this->addJsmethod($ret, 'retrySendRequest', 'retry');

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->setProp($module, $props, 'class', 'top');
        $this->setProp($module, $props, 'loading-msg', GD_CONSTANT_LOADING_MSG);
        $this->setProp($module, $props, 'loading-spinner', GD_CONSTANT_LOADING_SPINNER);
        
        parent::initModelProps($module, $props);
    }
}
