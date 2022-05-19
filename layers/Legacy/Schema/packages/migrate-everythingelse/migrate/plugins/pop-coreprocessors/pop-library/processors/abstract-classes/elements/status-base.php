<?php

abstract class PoP_Module_Processor_StatusBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_STATUS];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

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
            $this->getProp($component, $props, 'loading-spinner'),
            $this->getProp($component, $props, 'loading-msg')
        );
        $ret[GD_JS_TITLES]['loading'] = $loading;
        $ret[GD_JS_TITLES]['retry'] = GD_CONSTANT_RETRY_MSG;

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'switchTargetClass', 'error-dismiss');
        $this->addJsmethod($ret, 'retrySendRequest', 'retry');

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'class', 'top');
        $this->setProp($component, $props, 'loading-msg', GD_CONSTANT_LOADING_MSG);
        $this->setProp($component, $props, 'loading-spinner', GD_CONSTANT_LOADING_SPINNER);

        parent::initModelProps($component, $props);
    }
}
