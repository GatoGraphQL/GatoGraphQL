<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoPCMSSchema\UserStateMutations\MutationResolvers\MutationInputProperties as UpstreamMutationInputProperties;

class MutationInputProperties extends UpstreamMutationInputProperties
{
    public final const USER_LOGIN = 'userLogin';
    public final const CODE = 'code';
    public final const REPEAT_PASSWORD = 'repeatPassword';
}
