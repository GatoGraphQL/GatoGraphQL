<?php

namespace GraphQLAPI\PluginUtils\Services\Helpers;

use GraphQLAPI\PluginUtils\Services\Helpers\URLParamHelpers;
use PoP\Engine\AbstractTestCase;

class URLParamHelpersTest extends AbstractTestCase
{
    public function testEncodeURIComponent(): void
    {
        /** @var URLParamHelpers */
        $urlParamHelpers = $this->getService(URLParamHelpers::class);

        /**
         * Inputs taken from Mozilla documentation for `encodeURIComponent`
         * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/encodeURIComponent
         */
        $inputOutputs = [
            'test?' => 'test%3F',
            'шеллы' => '%D1%88%D0%B5%D0%BB%D0%BB%D1%8B',
            ';,/?:@&=+$' => '%3B%2C%2F%3F%3A%40%26%3D%2B%24',
            "-_.!~*'()" => "-_.!~*'()",
            '#' => '%23',
            'ABC abc 123' => 'ABC%20abc%20123',
        ];
        foreach ($inputOutputs as $input => $expectedOutput) {
            $this->assertSame(
                $expectedOutput,
                $urlParamHelpers->encodeURIComponent($input)
            );
        }
    }
}
