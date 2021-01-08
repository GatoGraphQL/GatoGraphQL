<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Set\ValueObject\DowngradeSetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [
        // @todo Uncomment when PHP 8.0 released
        // DowngradeSetList::PHP_80,
        DowngradeSetList::PHP_74,
        DowngradeSetList::PHP_73,
        DowngradeSetList::PHP_72,
    ]);

    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);
};
