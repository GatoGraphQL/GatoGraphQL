<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Constants\Params;
use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class WarningFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const W1 = 'w1';
    public const W2 = 'w2';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::W1,
            self::W2,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::W1 => $this->__('URL param \'' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '\' expects the type and field name separated by \'' . Constants::TYPE_FIELD_SEPARATOR . '\' (eg: \'?' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '[Post' . Constants::TYPE_FIELD_SEPARATOR . 'title]=^0.1\'), so the following value has been ignored: \'%s\'', 'component-model'),
            self::W2 => $this->__('The ObjectTypeFieldResolver used to process field with name \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::WARNING;
    }
}
