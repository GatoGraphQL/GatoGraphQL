<?php

class PoP_Module_Processor_UploadPictureFileUpload extends PoP_Module_Processor_UploadPictureFileUploadBase
{
    public final const COMPONENT_FILEUPLOAD_PICTURE_UPLOAD = 'fileupload-picture-upload';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILEUPLOAD_PICTURE_UPLOAD],
        );
    }
}



