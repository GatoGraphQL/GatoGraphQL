<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoPCMSSchema\UserStateMutations\MutationResolvers\MutationInputProperties as UpstreamMutationInputProperties;

class MutationInputProperties extends UpstreamMutationInputProperties
{
    public const USER_LOGIN = 'userLogin';
    public const CODE = 'code';
    public const REPEAT_PASSWORD = 'repeatPassword';
}
