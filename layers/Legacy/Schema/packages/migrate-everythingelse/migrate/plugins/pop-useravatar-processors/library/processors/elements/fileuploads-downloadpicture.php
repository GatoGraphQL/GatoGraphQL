<?php

class PoP_Module_Processor_DownloadPictureFileUpload extends PoP_Module_Processor_DownloadPictureFileUploadBase
{
    public final const COMPONENT_FILEUPLOAD_PICTURE_DOWNLOAD = 'fileupload-picture-download';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILEUPLOAD_PICTURE_DOWNLOAD],
        );
    }
}



