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
    public const PLUGIN_CONFIG_ENTRIES = 'plugin_config_entries';
    /**
     * @var string
     */
    public const ADDITIONAL_DOWNGRADE_RECTOR_CONFIGS = 'additional_downgrade_rector_configs';
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
    public const UNMIGRATED_FAILING_SOURCE_PACKAGES = 'unmigrated_failing_source_packages';
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
    public const OUTPUT_FILE = 'output-file';
    /**
     * @var string
     */
    public const SCOPED_ONLY = 'scoped-only';
}
