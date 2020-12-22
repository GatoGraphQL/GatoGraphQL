<?php
namespace PoPSchema\Media;

interface FunctionAPI
{
    public function getMediaObject($media_id);
    public function getMediaDescription($media_id);
    public function getMediaSrc($image_id, $size = null);
    public function getMediaMimeType($media_id);
    public function getMediaAuthorId($media_id): int;
    public function getMediaElements(array $query, array $options = []): array;
}
