/**
 * This value is not defined as PoP\AccessControl\Schema\SchemaModes::DEFAULT_SCHEMA_MODE,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const DEFAULT_SCHEMA_MODE = 'default';
/**
 * Same value as in PoP\AccessControl\Schema\SchemaModes::PUBLIC_SCHEMA_MODE
 */
const PUBLIC_SCHEMA_MODE = 'public';
/**
 * Same value as in PoP\AccessControl\Schema\SchemaModes:OUT
 */
const PRIVATE_SCHEMA_MODE = 'private';

export { DEFAULT_SCHEMA_MODE, PUBLIC_SCHEMA_MODE, PRIVATE_SCHEMA_MODE };
