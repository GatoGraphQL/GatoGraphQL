<?php

class PoP_Module_Processor_ShareContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const COMPONENT_CONTENTINNER_EMBEDPREVIEW = 'contentinner-embedpreview';
    public final const COMPONENT_CONTENTINNER_EMBED = 'contentinner-embed';
    public final const COMPONENT_CONTENTINNER_API = 'contentinner-api';
    public final const COMPONENT_CONTENTINNER_COPYSEARCHURL = 'contentinner-copysearchurl';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENTINNER_EMBEDPREVIEW,
            self::COMPONENT_CONTENTINNER_EMBED,
            self::COMPONENT_CONTENTINNER_API,
            self::COMPONENT_CONTENTINNER_COPYSEARCHURL,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CONTENTINNER_EMBEDPREVIEW:
                $ret[] = [PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_EMBEDPREVIEW];
                break;

            case self::COMPONENT_CONTENTINNER_EMBED:
                $ret[] = [PoP_Module_Processor_ShareTextareaFormInputs::class, PoP_Module_Processor_ShareTextareaFormInputs::COMPONENT_FORMINPUT_EMBEDCODE];
                break;

            case self::COMPONENT_CONTENTINNER_API:
                $ret[] = [PoP_Module_Processor_ShareTextFormInputs::class, PoP_Module_Processor_ShareTextFormInputs::COMPONENT_FORMINPUT_API];
                break;

            case self::COMPONENT_CONTENTINNER_COPYSEARCHURL:
                $ret[] = [PoP_Module_Processor_ShareTextFormInputs::class, PoP_Module_Processor_ShareTextFormInputs::COMPONENT_FORMINPUT_COPYSEARCHURL];
                break;
        }

        return $ret;
    }
}


