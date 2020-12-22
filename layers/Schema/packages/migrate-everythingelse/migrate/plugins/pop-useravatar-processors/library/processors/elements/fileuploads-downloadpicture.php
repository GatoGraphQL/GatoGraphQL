<?php

class PoP_Module_Processor_DownloadPictureFileUpload extends PoP_Module_Processor_DownloadPictureFileUploadBase
{
    public const MODULE_FILEUPLOAD_PICTURE_DOWNLOAD = 'fileupload-picture-download';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILEUPLOAD_PICTURE_DOWNLOAD],
        );
    }
}



