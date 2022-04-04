<?php

class PoP_Module_Processor_FeaturedImageInnerComponentInputs extends PoP_Module_Processor_FeaturedImageInnerFormInputsBase
{
    public final const MODULE_FORMINPUT_FEATUREDIMAGEINNER = 'forminput-featuredimage-inner';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_FEATUREDIMAGEINNER],
        );
    }
}



