<?php
namespace PoPSchema\Media;

interface FunctionAPI
{
    public function getMediaObject($media_id);
    public function getMediaDescription($media_id);
    public function getMediaMimeType($media_id);
}
