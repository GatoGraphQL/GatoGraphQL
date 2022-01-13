<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LatestCountsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LATESTCOUNT];
    }

    public function getClasses(array $module, array &$props)
    {
        return array();
    }

    public function getLinkClass(array $module, array &$props)
    {
        return 'btn btn-link';
    }

    public function getWrapperClass(array $module, array &$props)
    {
        return 'text-center alert alert-info alert-sm';
    }

    public function getObjectName(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('post', 'pop-coreprocessors');
    }

    public function getObjectNames(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('posts', 'pop-coreprocessors');
    }

    public function getTitle(array $module, array &$props)
    {
        return
        '<i class="fa fa-fw fa-eye"></i>'.
        sprintf(
            TranslationAPIFacade::getInstance()->__('View %s new %s', 'pop-coreprocessors'),
            '<span class="pop-count">0</span>',
            sprintf(
                '<span class="pop-singular">%s</span><span class="pop-plural">%s</span>',
                $this->getObjectName($module, $props),
                $this->getObjectNames($module, $props)
            )
        );
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_TITLES]['link'] = $this->getTitle($module, $props);

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['latestcount'] = implode(' ', $this->getClasses($module, $props));
        $ret[GD_JS_CLASSES]['link'] = $this->getLinkClass($module, $props);

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'loadLatestBlock');

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Initially hidden
        $this->appendProp($module, $props, 'class', 'hidden pop-latestcount');

        if ($wrapper_class = $this->getWrapperClass($module, $props)) {
            $this->appendProp($module, $props, 'class', $wrapper_class);
        }
        parent::initModelProps($module, $props);
    }
}
