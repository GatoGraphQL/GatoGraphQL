/**
 * This value is not defined as GatoGraphQL\GatoGraphQLPRO\Constants\GlobalFieldsSchemaExposure::DEFAULT_SCHEMA_EXPOSURE,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const DEFAULT_SCHEMA_EXPOSURE = 'default';
/**
 * Same value as in GatoGraphQL\GatoGraphQLPRO\Constants\GlobalFieldsSchemaExposure::DO_NOT_EXPOSE
 */
const DO_NOT_EXPOSE = 'do-not-expose';
/**
 * Same value as in GatoGraphQL\GatoGraphQLPRO\Constants\GlobalFieldsSchemaExposure:EXPOSE_IN_ROOT_TYPE_ONLY
 */
const EXPOSE_IN_ROOT_TYPE_ONLY = 'expose-in-root-type-only';
/**
 * Same value as in GatoGraphQL\GatoGraphQLPRO\Constants\GlobalFieldsSchemaExposure:EXPOSE_IN_ALL_TYPES
 */
const EXPOSE_IN_ALL_TYPES = 'expose-in-all-types';

export {
    DEFAULT_SCHEMA_EXPOSURE,
    DO_NOT_EXPOSE,
    EXPOSE_IN_ROOT_TYPE_ONLY,
    EXPOSE_IN_ALL_TYPES
};
