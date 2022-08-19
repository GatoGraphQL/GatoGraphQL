<?php

class PoP_Module_Processor_DownloadPictureFileUpload extends PoP_Module_Processor_DownloadPictureFileUploadBase
{
    public final const COMPONENT_FILEUPLOAD_PICTURE_DOWNLOAD = 'fileupload-picture-download';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILEUPLOAD_PICTURE_DOWNLOAD,
        );
    }
}



