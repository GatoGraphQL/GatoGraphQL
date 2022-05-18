<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LatestCountsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LATESTCOUNT];
    }

    public function getClasses(array $componentVariation, array &$props)
    {
        return array();
    }

    public function getLinkClass(array $componentVariation, array &$props)
    {
        return 'btn btn-link';
    }

    public function getWrapperClass(array $componentVariation, array &$props)
    {
        return 'text-center alert alert-info alert-sm';
    }

    public function getObjectName(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('post', 'pop-coreprocessors');
    }

    public function getObjectNames(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('posts', 'pop-coreprocessors');
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        return
        '<i class="fa fa-fw fa-eye"></i>'.
        sprintf(
            TranslationAPIFacade::getInstance()->__('View %s new %s', 'pop-coreprocessors'),
            '<span class="pop-count">0</span>',
            sprintf(
                '<span class="pop-singular">%s</span><span class="pop-plural">%s</span>',
                $this->getObjectName($componentVariation, $props),
                $this->getObjectNames($componentVariation, $props)
            )
        );
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_TITLES]['link'] = $this->getTitle($componentVariation, $props);

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        $ret[GD_JS_CLASSES]['latestcount'] = implode(' ', $this->getClasses($componentVariation, $props));
        $ret[GD_JS_CLASSES]['link'] = $this->getLinkClass($componentVariation, $props);

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'loadLatestBlock');

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Initially hidden
        $this->appendProp($componentVariation, $props, 'class', 'hidden pop-latestcount');

        if ($wrapper_class = $this->getWrapperClass($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', $wrapper_class);
        }
        parent::initModelProps($componentVariation, $props);
    }
}
