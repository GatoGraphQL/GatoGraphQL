<?php

declare(strict_types=1);

namespace PoPSchema\CommentsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Actions
        \PoP\Root\App::getHookManager()->addAction('wp_insert_comment', function ($comment_id, $comment): void {
            \PoP\Root\App::getHookManager()->doAction('popcms:insertComment', $comment_id, $comment);
        }, 10, 2);
        \PoP\Root\App::getHookManager()->addAction('spam_comment', function ($comment_id, $comment): void {
            \PoP\Root\App::getHookManager()->doAction('popcms:spamComment', $comment_id, $comment);
        }, 10, 2);
        \PoP\Root\App::getHookManager()->addAction('delete_comment', function ($comment_id, $comment): void {
            \PoP\Root\App::getHookManager()->doAction('popcms:deleteComment', $comment_id, $comment);
        }, 10, 2);

        $this->getLooseContractManager()->implementHooks([
            'popcms:insertComment',
            'popcms:spamComment',
            'popcms:deleteComment',
        ]);

        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:comments:date' => 'comment_date_gmt',
            'popcms:dbcolumn:orderby:customposts:comment-count' => 'comment_count',
        ]);
    }
}
