<?php

class PoP_Module_Processor_UploadPictureFileUpload extends PoP_Module_Processor_UploadPictureFileUploadBase
{
    public const MODULE_FILEUPLOAD_PICTURE_UPLOAD = 'fileupload-picture-upload';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILEUPLOAD_PICTURE_UPLOAD],
        );
    }
}



