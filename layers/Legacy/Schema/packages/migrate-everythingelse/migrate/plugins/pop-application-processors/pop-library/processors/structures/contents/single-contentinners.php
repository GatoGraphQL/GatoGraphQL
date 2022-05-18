<?php

class PoP_Module_Processor_SingleContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_CONTENTINNER_AUTHOR = 'contentinner-author';
    public final const MODULE_CONTENTINNER_SINGLE = 'contentinner-single';
    public final const MODULE_CONTENTINNER_HIGHLIGHTSINGLE = 'contentinner-highlightsingle';
    public final const MODULE_CONTENTINNER_USERPOSTINTERACTION = 'contentinner-userpostinteraction';
    public final const MODULE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION = 'contentinner-userhighlightpostinteraction';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_AUTHOR],
            [self::class, self::MODULE_CONTENTINNER_SINGLE],
            [self::class, self::MODULE_CONTENTINNER_HIGHLIGHTSINGLE],
            [self::class, self::MODULE_CONTENTINNER_USERPOSTINTERACTION],
            [self::class, self::MODULE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_CONTENTINNER_AUTHOR:
                $ret[] = [PoP_Module_Processor_AuthorContentLayouts::class, PoP_Module_Processor_AuthorContentLayouts::MODULE_LAYOUTAUTHOR_CONTENT];
                break;

            case self::MODULE_CONTENTINNER_SINGLE:
            case self::MODULE_CONTENTINNER_HIGHLIGHTSINGLE:
                $ret[] = [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST];
                break;

            case self::MODULE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayoutWrappers::class, Wassup_Module_Processor_MultipleComponentLayoutWrappers::MODULE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION];
                break;

            case self::MODULE_CONTENTINNER_USERPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayoutWrappers::class, Wassup_Module_Processor_MultipleComponentLayoutWrappers::MODULE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_CONTENTINNER_HIGHLIGHTSINGLE:
                // Highlights: it has a different set-up
                $this->appendProp($component, $props, 'class', 'well');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


