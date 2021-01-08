<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject;

final class Option
{
    /**
     * @var string
     */
    public const PACKAGE_ORGANIZATIONS = 'package_organizations';
    /**
     * @var string
     */
    public const JSON = 'json';
    /**
     * @var string
     */
    public const PSR4_ONLY = 'psr4-only';
    /**
     * @var string
     */
    public const SKIP_UNMIGRATED = 'skip-unmigrated';
    /**
     * @var string
     */
    public const SUBFOLDER = 'subfolder';
    /**
     * @var string
     */
    public const FILTER = 'filter';
    /**
     * @var string
     */
    public const FILTER_LIST = 'filter-list';
}
