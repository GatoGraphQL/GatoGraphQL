<?php

class PoP_Module_Processor_ShareContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_CONTENTINNER_EMBEDPREVIEW = 'contentinner-embedpreview';
    public final const MODULE_CONTENTINNER_EMBED = 'contentinner-embed';
    public final const MODULE_CONTENTINNER_API = 'contentinner-api';
    public final const MODULE_CONTENTINNER_COPYSEARCHURL = 'contentinner-copysearchurl';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_EMBEDPREVIEW],
            [self::class, self::MODULE_CONTENTINNER_EMBED],
            [self::class, self::MODULE_CONTENTINNER_API],
            [self::class, self::MODULE_CONTENTINNER_COPYSEARCHURL],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_CONTENTINNER_EMBEDPREVIEW:
                $ret[] = [PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_EMBEDPREVIEW];
                break;

            case self::MODULE_CONTENTINNER_EMBED:
                $ret[] = [PoP_Module_Processor_ShareTextareaFormInputs::class, PoP_Module_Processor_ShareTextareaFormInputs::MODULE_FORMINPUT_EMBEDCODE];
                break;

            case self::MODULE_CONTENTINNER_API:
                $ret[] = [PoP_Module_Processor_ShareTextFormInputs::class, PoP_Module_Processor_ShareTextFormInputs::MODULE_FORMINPUT_API];
                break;

            case self::MODULE_CONTENTINNER_COPYSEARCHURL:
                $ret[] = [PoP_Module_Processor_ShareTextFormInputs::class, PoP_Module_Processor_ShareTextFormInputs::MODULE_FORMINPUT_COPYSEARCHURL];
                break;
        }

        return $ret;
    }
}


