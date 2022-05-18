<?php

class PoP_Module_Processor_FeaturedImageInnerComponentInputs extends PoP_Module_Processor_FeaturedImageInnerFormInputsBase
{
    public final const MODULE_FORMINPUT_FEATUREDIMAGEINNER = 'forminput-featuredimage-inner';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_FEATUREDIMAGEINNER],
        );
    }
}



