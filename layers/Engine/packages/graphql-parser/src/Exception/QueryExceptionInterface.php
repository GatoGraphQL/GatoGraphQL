<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use Throwable;

interface QueryExceptionInterface extends Throwable
{
    public function getFeedbackItemResolution(): FeedbackItemResolution;
    public function getAstNode(): AstInterface;
}
