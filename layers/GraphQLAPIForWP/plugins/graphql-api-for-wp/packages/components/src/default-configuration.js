/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';


/**
 * Same default value as for environment variable `GROUP_FIELDS_UNDER_TYPE_FOR_PRINT` in PHP
 */
export const GROUP_FIELDS_UNDER_TYPE_FOR_PRINT = true;
/**
 * Same default value as for environment variable `EMPTY_LABEL` in PHP
 */
export const EMPTY_LABEL = __('---', 'graphql-api');
/**
 * Same default value as for environment variable `SETTINGS_VALUE_LABEL` in PHP
 */
// export const SETTINGS_VALUE_LABEL = __('As defined in the General Settings', 'graphql-api');
export const SETTINGS_VALUE_LABEL = __('Default', 'graphql-api');
