<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject;

final class Option
{
    /**
     * @var string
     */
    public const PACKAGE_ORGANIZATIONS = 'package-organizations';
    /**
     * @var string
     */
    public const PLUGIN_CONFIG_ENTRIES = 'plugin-config-entries';
    /**
     * @var string
     */
    public const SKIP_DOWNGRADE_TEST_FILES = 'skip-downgrade-test-files';
    /**
     * @var string
     */
    public const ADDITIONAL_DOWNGRADE_RECTOR_CONFIGS = 'additional-downgrade-rector-configs';
    /**
     * @var string
     */
    public const ENVIRONMENT_VARIABLES = 'environment-variables';
    /**
     * @var string
     */
    public const ENVIRONMENT_VARIABLE_NAME = 'environment-variable-name';
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
    public const UNMIGRATED_FAILING_PACKAGES = 'unmigrated-failing-packages';
    /**
     * @var string
     */
    public const LEVEL = 'level';
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
    /**
     * @var string
     */
    public const RELATIVE = 'relative';
}
