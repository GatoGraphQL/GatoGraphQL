<?php

/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser;

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
