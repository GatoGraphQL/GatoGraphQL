<?php

declare(strict_types=1);

namespace PoPSchema\CommentsWP\LooseContracts;

use PoP\Root\App;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Actions
        App::addAction('spam_comment', function ($comment_id, $comment): void {
            App::doAction('popcms:spamComment', $comment_id, $comment);
        }, 10, 2);
        App::addAction('delete_comment', function ($comment_id, $comment): void {
            App::doAction('popcms:deleteComment', $comment_id, $comment);
        }, 10, 2);

        $this->getLooseContractManager()->implementHooks([
            'popcms:spamComment',
            'popcms:deleteComment',
        ]);

        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:comments:date' => 'comment_date_gmt',
            'popcms:dbcolumn:orderby:customposts:comment-count' => 'comment_count',
        ]);
    }
}
