<?php

function getAttachmentImageProperties($imageid, $size = null)
{
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $img = $cmsapi->wpGetAttachmentImageSrc($imageid, $size);
    return array(
        'src' => $img[0],
        'width' => $img[1],
        'height' => $img[2]
    );
}
