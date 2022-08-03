<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

interface QueryExceptionInterface
{
    public function getFeedbackItemResolution(): FeedbackItemResolution;
    public function getAstNode(): AstInterface;
}
