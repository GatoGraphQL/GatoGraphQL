<?php

class PoP_Module_Processor_Contents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_AUTHOR = 'content-author';
    public final const MODULE_CONTENT_SINGLE = 'content-single';
    public final const MODULE_CONTENT_USERPOSTINTERACTION = 'content-userpostinteraction';
    public final const MODULE_CONTENT_PAGECONTENT = 'content-pagecontent';
    public final const MODULE_CONTENT_PAGECONTENT_PRETTYPRINT = 'content-pagecontent-prettyprint';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENT_AUTHOR],
            [self::class, self::COMPONENT_CONTENT_SINGLE],
            [self::class, self::COMPONENT_CONTENT_USERPOSTINTERACTION],
            [self::class, self::COMPONENT_CONTENT_PAGECONTENT],
            [self::class, self::COMPONENT_CONTENT_PAGECONTENT_PRETTYPRINT],
        );
    }
    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_CONTENT_AUTHOR => [PoP_Module_Processor_SingleContentInners::class, PoP_Module_Processor_SingleContentInners::COMPONENT_CONTENTINNER_AUTHOR],
            self::COMPONENT_CONTENT_PAGECONTENT => [PoP_Module_Processor_MultipleContentInners::class, PoP_Module_Processor_MultipleContentInners::COMPONENT_CONTENTINNER_PAGECONTENT],
            self::COMPONENT_CONTENT_PAGECONTENT_PRETTYPRINT => [PoP_Module_Processor_MultipleContentInners::class, PoP_Module_Processor_MultipleContentInners::COMPONENT_CONTENTINNER_PAGECONTENT],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        $hookable = array(
            [self::class, self::COMPONENT_CONTENT_SINGLE],
            [self::class, self::COMPONENT_CONTENT_USERPOSTINTERACTION],
        );
        if (in_array($component, $hookable)) {
            $inners = array(
                self::COMPONENT_CONTENT_SINGLE => [PoP_Module_Processor_SingleContentInners::class, PoP_Module_Processor_SingleContentInners::COMPONENT_CONTENTINNER_SINGLE],
                self::COMPONENT_CONTENT_USERPOSTINTERACTION => [PoP_Module_Processor_SingleContentInners::class, PoP_Module_Processor_SingleContentInners::COMPONENT_CONTENTINNER_USERPOSTINTERACTION],
            );
            $inner = $inners[$component[1]];

            return \PoP\Root\App::applyFilters('PoP_Module_Processor_Contents:inner_component', $inner, $component);
        }

        return parent::getInnerSubmodule($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_CONTENT_PAGECONTENT_PRETTYPRINT:
                $this->addJsmethod($ret, 'prettyPrint');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENT_SINGLE:
                $this->appendProp($component, $props, 'class', 'content-single');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


