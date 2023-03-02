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
import { getModulePRODocMarkdownContentOrUseDefault } from '../module-pro-doc-markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'schema-configuration-additional-documentation-pro-panel';
/**
 * Component
 */
const implicitFeaturesDocEntries = [
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
        'Pass Onwards Directive',
        'pass-onwards-directive'
    ],
    [
        'Remove Output Directive',
        'remove-directive'
    ],
];
const displayUnlockPROPluginMessage = window.schemaConfigurationAdditionalDocumentationPro.displayUnlockPROPluginMessage;
const proPluginWebsiteURL = window.schemaConfigurationAdditionalDocumentationPro.proPluginWebsiteURL;
const title = displayUnlockPROPluginMessage
    ? __('🔒 Additional PRO Documentation', 'graphql-api')
    : __('Additional PRO Documentation', 'graphql-api');
const buttonClassName = "graphql-api-info-modal-button text-wrap";
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ title }
    >
        { displayUnlockPROPluginMessage &&
            <p>
                <GoProLink
                    proPluginWebsiteURL={ proPluginWebsiteURL }
                />
            </p>
        }
        <p>{ __('Docs for additional features unlocked by the GraphQL API PRO:', 'graphql-api') }</p>
        <p>
            {
                implicitFeaturesDocEntries.map( ( entry ) =>
                    <MarkdownInfoModalButton
                        text={ entry[0] }
                        title={ __(`Documentation for: "${ entry[0] }"`, 'graphql-api') }
                        pageFilename={ entry[1] }
                        getMarkdownContentCallback={ getImplicitFeaturesDocMarkdownContentOrUseDefault }
                        isSmall={ false }
                        className={ buttonClassName }
                    />
                )
            }
            <hr/>
            {
                modulePRODocEntries.map( ( entry ) =>
                    <MarkdownInfoModalButton
                        text={ entry[0] }
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
