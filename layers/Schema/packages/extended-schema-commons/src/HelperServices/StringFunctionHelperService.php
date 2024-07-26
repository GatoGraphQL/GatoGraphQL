<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\HelperServices;

use PoP\Root\Services\BasicServiceTrait;

use function preg_quote;

class StringFunctionHelperService implements StringFunctionHelperServiceInterface
{
    use BasicServiceTrait;

    /**
     * PHP 7.2 does not quote `#`, but PHP 7.3 onwards does.
     * Hence, add this backward compatibility for when running 7.2.
     *
     * @see https://www.php.net/manual/en/function.preg-quote.php#refsect1-function.preg-quote-changelog
     */
    public function quoteRegex(
        string $string,
        ?string $delimiter = null,
    ): string {
        $value = preg_quote($string, $delimiter);
        if (\PHP_VERSION_ID < 70300 && $delimiter !== '#') {
            $value = str_replace($value, '#', '\\#');
        }
        return $value;
    }
}
