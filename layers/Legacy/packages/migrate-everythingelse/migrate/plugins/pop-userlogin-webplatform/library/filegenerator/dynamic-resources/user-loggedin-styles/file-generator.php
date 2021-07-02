<?php
class PoP_CoreProcessors_UserLoggedInStyles_File extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
{
    public function getDir(): string
    {
        return POP_USERLOGIN_ASSETDESTINATION_DIR;
    }
    public function getUrl(): string
    {
        return POP_USERLOGIN_ASSETDESTINATION_URL;
    }

    public function getFilename(): string
    {
        return 'user-loggedin.css';
    }

    // public function getRenderer()
    // {
    //     global $popcore_userloggedinstyles_filerenderer;
    //     return $popcore_userloggedinstyles_filerenderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_CoreProcessors_FileReproduction_UserLoggedInStyles(),
        ];
    }
}

/**
 * Initialize
 */
global $popcore_userloggedinstyles_file;
$popcore_userloggedinstyles_file = new PoP_CoreProcessors_UserLoggedInStyles_File();
