<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Constants;

class GlobalFieldsSchemaExposure
{
    public final const DO_NOT_EXPOSE = 'do-not-expose';
    public final const EXPOSE_IN_ROOT_TYPE_ONLY = 'expose-in-root-type-only';
    public final const EXPOSE_IN_ALL_TYPES = 'expose-in-all-types';
}
