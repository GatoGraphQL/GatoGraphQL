<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectModels;

/**
 * Mutations that do not modify an object, such as `Root._sendEmail`
 */
final class MutationPayload extends AbstractTransientOperationPayload
{
}
