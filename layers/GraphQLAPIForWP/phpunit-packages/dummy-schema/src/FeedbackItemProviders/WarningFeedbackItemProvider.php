<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\DummySchema\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class WarningFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const W1 = 'w1';
    public final const W2 = 'w2';
    public final const W3 = 'w3';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::W1,
            self::W2,
            self::W3,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::W1 => $this->__('Field \'%1$s\' has a new version: \'%2$s\'. This version will become the default one on January 1st. We advise you to use this new version already and test that it works fine; if you find any problem, please report the issue in %3$s. To do the switch, please add the \'versionConstraint\' field argument to your query, using Composer\'s semver constraint rules (%4$s): %1$s(versionConstraint:"%5$s"). If you are unable to switch to the new version, please make sure to explicitly point to the current version \'%6$s\' before January 1st: %1$s(versionConstraint:"%6$s"). In case of doubt, please contact us at name@company.com.', 'dummy-schema'),
            self::W2 => $this->__('Field \'%1$s\' has more than 1 version. Please add the \'versionConstraint\' field argument to your query to indicate which version to use (using Composer\'s semver constraint rules: %2$s). To use the latest version, use: %1$s(versionConstraint:"%3$s"). Available versions: \'%4$s\'.', 'dummy-schema'),
            self::W3 => $this->__('Directive \'%1$s\' has a new version: \'%2$s\'. This version will become the default one on January 1st. We advise you to use this new version already and test that it works fine; if you find any problem, please report the issue in %3$s. To do the switch, please add the \'versionConstraint\' directive argument to your query, using Composer\'s semver constraint rules (%4$s): @%1$s(versionConstraint:"%5$s"). If you are unable to switch to the new version, please make sure to explicitly point to the current version \'%6$s\' before January 1st: @%1$s(versionConstraint:"%6$s"). In case of doubt, please contact us at name@company.com.', 'dummy-schema'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::WARNING;
    }
}
