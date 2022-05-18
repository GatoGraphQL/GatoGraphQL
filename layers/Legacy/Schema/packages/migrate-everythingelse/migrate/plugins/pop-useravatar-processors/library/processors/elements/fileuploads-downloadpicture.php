<?php

class PoP_Module_Processor_DownloadPictureFileUpload extends PoP_Module_Processor_DownloadPictureFileUploadBase
{
    public final const MODULE_FILEUPLOAD_PICTURE_DOWNLOAD = 'fileupload-picture-download';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILEUPLOAD_PICTURE_DOWNLOAD],
        );
    }
}



