<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\CMS;

interface CMSDataConversionServiceInterface
{
    public function convertCustomPostStatusFromPoPToCMS(string $status): string;
}
