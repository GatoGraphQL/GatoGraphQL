<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs;

interface UserMediaTypeAPIInterface
{
    public function getMediaAuthorId(string | int | object $mediaObjectOrID): string | int | null;
}
