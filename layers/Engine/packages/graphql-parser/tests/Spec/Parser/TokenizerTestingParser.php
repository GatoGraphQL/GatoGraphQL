<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

class TokenizerTestingParser extends Parser
{
    public function initTokenizerForTesting(string $source): void
    {
        $this->initTokenizer($source);
    }

    public function getTokenForTesting(): Token
    {
        return $this->lookAhead;
    }
}
