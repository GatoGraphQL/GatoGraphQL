<?php

interface PoP_PreviewContent_FunctionsAPI
{
    public function getPreviewLink($post_id);
    public function setPreview($post_id);
    public function removePreview($post_id);
}
