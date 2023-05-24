<?php

declare(strict_types=1);

namespace PoPSchema\HTTPRequests\Enums;

class HTTPRequestMethodEnum
{
    public final const GET = 'GET';
    public final const POST = 'POST';
    public final const PUT = 'PUT';
    public final const DELETE = 'DELETE';
    public final const PATCH = 'PATCH';
    public final const HEAD = 'HEAD';
    public final const OPTIONS = 'OPTIONS';
}
