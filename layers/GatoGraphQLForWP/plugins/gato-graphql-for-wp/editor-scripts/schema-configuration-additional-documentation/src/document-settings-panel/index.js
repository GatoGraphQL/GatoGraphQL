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
const moduleAndImplicitFeaturesPRODocEntries = [
    [
        'Apply Field Directive',
        'apply-field-directive',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Cache Directive',
        'cache-directive',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Custom Scalars Pack',
        'custom-scalars',
        getImplicitFeaturesPRODocMarkdownContentOrUseDefault
    ],
    [
        'Default Directive',
        'default-directive',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Deprecation Notifier',
        'deprecation-notifier',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Dynamic Variables',
        'dynamic-variables',
        getImplicitFeaturesPRODocMarkdownContentOrUseDefault
    ],
    [
        'Function Directives',
        'function-directives',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Function Fields',
        'function-fields',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Helper Fields',
        'helper-fields',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Inspect HTTP Request Fields',
        'inspect-http-request-fields',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Meta Directives',
        'meta-directives',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Pass Onwards Directive',
        'pass-onwards-directive',
        getModulePRODocMarkdownContentOrUseDefault
    ],
    [
        'Remove Output Directive',
        'remove-directive',
        getModulePRODocMarkdownContentOrUseDefault
    ],
];
const displayUnlockPROPluginMessage = window.schemaConfigurationAdditionalDocumentation.displayUnlockPROPluginMessage;
const proPluginWebsiteURL = window.schemaConfigurationAdditionalDocumentation.proPluginWebsiteURL;
const buttonClassName = "gato-graphql-info-modal-button text-wrap";
const proTitlePrefix = displayUnlockPROPluginMessage ? __('🔒 ', 'gato-graphql') : '';
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Additional GraphQL API Documentation', 'gato-graphql') }
    >
        <p>{ __('Docs for additional features in the GraphQL API:', 'gato-graphql') }</p>
        <p>
            {
                implicitFeaturesDocEntries.map( ( entry ) =>
                    <div>
                            <MarkdownInfoModalButton
                            text={ entry[0] }
                            title={ __(`Documentation for: "${ entry[0] }"`, 'gato-graphql') }
                            pageFilename={ entry[1] }
                            getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
                            isSmall={ false }
                            className="gato-graphql-info-modal-button text-wrap"
                        />
                    </div>
                )
            }
        </p>
        <hr/>
        <p>
            { __('Docs for additional features in the GraphQL API PRO:', 'gato-graphql') }
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
                moduleAndImplicitFeaturesPRODocEntries.map( ( entry ) =>
                    <div>
                        <MarkdownInfoModalButton
                            text={ proTitlePrefix + entry[0] }
                            title={ __(`Documentation for: "${ entry[0] }"`, 'gato-graphql') }
                            pageFilename={ entry[1] }
                            getMarkdownContentCallback={ entry[2] }
                            isSmall={ false }
                            className={ buttonClassName }
                        />
                    </div>
                )
            }
        </p>
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
