<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

class TokenizerTestingParser extends Parser
{
    public function initTokenizerForTesting($source)
    {
        $this->initTokenizer($source);
    }

    public function getTokenForTesting()
    {
        return $this->lookAhead;
    }
}
