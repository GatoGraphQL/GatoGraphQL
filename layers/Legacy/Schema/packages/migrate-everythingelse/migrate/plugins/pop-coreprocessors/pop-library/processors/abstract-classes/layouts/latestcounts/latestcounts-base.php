<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LatestCountsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LATESTCOUNT];
    }

    public function getClasses(array $component, array &$props)
    {
        return array();
    }

    public function getLinkClass(array $component, array &$props)
    {
        return 'btn btn-link';
    }

    public function getWrapperClass(array $component, array &$props)
    {
        return 'text-center alert alert-info alert-sm';
    }

    public function getObjectName(array $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('post', 'pop-coreprocessors');
    }

    public function getObjectNames(array $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('posts', 'pop-coreprocessors');
    }

    public function getTitle(array $component, array &$props)
    {
        return
        '<i class="fa fa-fw fa-eye"></i>'.
        sprintf(
            TranslationAPIFacade::getInstance()->__('View %s new %s', 'pop-coreprocessors'),
            '<span class="pop-count">0</span>',
            sprintf(
                '<span class="pop-singular">%s</span><span class="pop-plural">%s</span>',
                $this->getObjectName($component, $props),
                $this->getObjectNames($component, $props)
            )
        );
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_TITLES]['link'] = $this->getTitle($component, $props);

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['latestcount'] = implode(' ', $this->getClasses($component, $props));
        $ret[GD_JS_CLASSES]['link'] = $this->getLinkClass($component, $props);

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'loadLatestBlock');

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Initially hidden
        $this->appendProp($component, $props, 'class', 'hidden pop-latestcount');

        if ($wrapper_class = $this->getWrapperClass($component, $props)) {
            $this->appendProp($component, $props, 'class', $wrapper_class);
        }
        parent::initModelProps($component, $props);
    }
}
