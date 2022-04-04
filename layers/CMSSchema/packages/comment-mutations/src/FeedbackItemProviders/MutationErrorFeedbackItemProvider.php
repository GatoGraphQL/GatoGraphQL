<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E3,
            self::E4,
            self::E5,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You are not logged in', 'user-state-mutations'),
            self::E2 => $this->__('The comment author\'s name is missing', 'comment-mutations'),
            self::E3 => $this->__('The comment author\'s email is missing', 'comment-mutations'),
            self::E4 => $this->__('The custom post ID is missing.', 'comment-mutations'),
            self::E5 => $this->__('The comment is empty.', 'comment-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
