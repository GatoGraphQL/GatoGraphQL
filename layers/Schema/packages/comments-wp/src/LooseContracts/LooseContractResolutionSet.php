<?php

declare(strict_types=1);

namespace PoPSchema\CommentsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Actions
        $this->hooksAPI->addAction('wp_insert_comment', function ($comment_id, $comment) {
            $this->hooksAPI->doAction('popcms:insertComment', $comment_id, $comment);
        }, 10, 2);
        $this->hooksAPI->addAction('spam_comment', function ($comment_id, $comment) {
            $this->hooksAPI->doAction('popcms:spamComment', $comment_id, $comment);
        }, 10, 2);
        $this->hooksAPI->addAction('delete_comment', function ($comment_id, $comment) {
            $this->hooksAPI->doAction('popcms:deleteComment', $comment_id, $comment);
        }, 10, 2);

        $this->looseContractManager->implementHooks([
            'popcms:insertComment',
            'popcms:spamComment',
            'popcms:deleteComment',
        ]);

        $this->nameResolver->implementNames([
            'popcms:dbcolumn:orderby:comments:date' => 'comment_date_gmt',
            'popcms:dbcolumn:orderby:customposts:comment-count' => 'comment_count',
        ]);
    }
}
