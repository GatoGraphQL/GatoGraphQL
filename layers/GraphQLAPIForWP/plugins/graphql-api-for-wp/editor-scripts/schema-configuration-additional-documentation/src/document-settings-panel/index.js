/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import {
    MarkdownInfoModalButton,
    GoProLink,
} from '@graphqlapi/components';
import { getImplicitFeaturesDocMarkdownContentOrUseDefault } from '../implicit-features-doc-markdown-loader';
import { getImplicitFeaturesPRODocMarkdownContentOrUseDefault } from '../implicit-features-pro-doc-markdown-loader';
import { getModulePRODocMarkdownContentOrUseDefault } from '../module-pro-doc-markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'schema-configuration-additional-documentation-panel';
/**
 * Component
 */
const implicitFeaturesDocEntries = [
    [
        'AnyBuiltInScalar Type',
        'any-built-in-scalar'
    ],
    [
        'DangerouslyNonSpecificScalar Type',
        'dangerously-non-specific-scalar'
    ],
    [
        'OneOf Input Object',
        'oneof-input-object'
    ],
    [
        'Query Schema Extensions via Introspection',
        'query-schema-extensions-via-introspection'
    ],
    [
        'Restrict Field Directives to Specific Types',
        'restrict-field-directives-to-specific-types'
    ],
];
const implicitFeaturesPRODocEntries = [
    [
        'Custom Scalars Pack',
        'custom-scalars'
    ],
    [
        'Dynamic Variables',
        'dynamic-variables'
    ],
];
const modulePRODocEntries = [
    [
        'Apply Field Directive',
        'apply-field-directive'
    ],
    [
        'Cache Directive',
        'cache-directive'
    ],
    [
        'Default Directive',
        'default-directive'
    ],
    [
        'Function Directives',
        'function-directives'
    ],
    [
        'Function Fields',
        'function-fields'
    ],
    [
        'Meta Directives',
        'meta-directives'
    ],
    [
        'Pass Onwards Directive',
        'pass-onwards-directive'
    ],
    [
        'Remove Output Directive',
        'remove-directive'
    ],
];
const displayUnlockPROPluginMessage = window.schemaConfigurationAdditionalDocumentation.displayUnlockPROPluginMessage;
const proPluginWebsiteURL = window.schemaConfigurationAdditionalDocumentation.proPluginWebsiteURL;
const buttonClassName = "graphql-api-info-modal-button text-wrap";
const proTitlePrefix = displayUnlockPROPluginMessage ? __('🔒 ', 'graphql-api') : '';
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Additional GraphQL API Documentation', 'graphql-api') }
    >
        <p>{ __('Docs for additional features in the GraphQL API:', 'graphql-api') }</p>
        <p>
            {
                implicitFeaturesDocEntries.map( ( entry ) =>
                    <MarkdownInfoModalButton
                        text={ entry[0] }
                        title={ __(`Documentation for: "${ entry[0] }"`, 'graphql-api') }
                        pageFilename={ entry[1] }
                        getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
                        isSmall={ false }
                        className="graphql-api-info-modal-button text-wrap"
                    />
                )
            }
        </p>
        <hr/>
        <p>
            { __('Docs for additional features in the GraphQL API PRO:', 'graphql-api') }
        </p>
        { displayUnlockPROPluginMessage &&
            <p>
                <GoProLink
                    proPluginWebsiteURL={ proPluginWebsiteURL }
                />
            </p>
        }
        <p>
            {
                implicitFeaturesPRODocEntries.map( ( entry ) =>
                    <MarkdownInfoModalButton
                        text={ proTitlePrefix + entry[0] }
                        title={ __(`Documentation for: "${ entry[0] }"`, 'graphql-api') }
                        pageFilename={ entry[1] }
                        getMarkdownContentCallback={ getImplicitFeaturesPRODocMarkdownContentOrUseDefault }
                        isSmall={ false }
                        className={ buttonClassName }
                    />
                )
            }
            <hr/>
            {
                modulePRODocEntries.map( ( entry ) =>
                    <MarkdownInfoModalButton
                        text={ proTitlePrefix + entry[0] }
                        title={ __(`Documentation for: "${ entry[0] }"`, 'graphql-api') }
                        pageFilename={ entry[1] }
                        getMarkdownContentCallback={ getModulePRODocMarkdownContentOrUseDefault }
                        isSmall={ false }
                        className={ buttonClassName }
                    />
                )
            }
        </p>
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
