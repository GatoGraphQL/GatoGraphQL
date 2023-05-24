<?php

declare(strict_types=1);

namespace PoPSchema\HTTPRequests\HelperServices;

interface HTTPRequestHelperServiceInterface
{
    /**
     * Both Guzzle and Symfony HTTP Foundation retrieve the
     * header values as `string[]`, but for the HTTP Request,
     * and in class HTTPResponse, the representation must
     * be `string`.
     *
     * So iterate all the headers and convert it from
     * array to string.
     *
     * @param array<string,string[]> $headers
     * @return array<string,string>
     */
    public function convertHeaderArrayValuesToSingleValue(array $headers): array;
}
