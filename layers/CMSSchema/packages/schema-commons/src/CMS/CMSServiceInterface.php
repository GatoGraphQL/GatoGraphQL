<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\CMS;

interface CMSServiceInterface
{
    public function getOption(string $option, mixed $default = false): mixed;
    public function getHomeURL(string $path = ''): string;
    public function getSiteURL(): string;
}
