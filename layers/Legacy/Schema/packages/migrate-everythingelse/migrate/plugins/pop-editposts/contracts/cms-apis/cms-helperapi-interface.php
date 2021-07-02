<?php
namespace PoP\EditPosts;

interface HelperAPI
{
    public function kses($string, $allowed_html = null);
}