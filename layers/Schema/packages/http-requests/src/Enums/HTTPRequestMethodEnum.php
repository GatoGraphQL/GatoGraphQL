<?php

declare(strict_types=1);

namespace PoPSchema\HTTPRequests\Enums;

enum HTTPRequestMethodEnum: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
}
