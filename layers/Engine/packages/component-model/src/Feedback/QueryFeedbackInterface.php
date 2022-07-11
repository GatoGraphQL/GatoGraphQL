<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;

interface QueryFeedbackInterface extends FeedbackInterface
{
    public function getAstNode(): AstInterface;
    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array;
}
