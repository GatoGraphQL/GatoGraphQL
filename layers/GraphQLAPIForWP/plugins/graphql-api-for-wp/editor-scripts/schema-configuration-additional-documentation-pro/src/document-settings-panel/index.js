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
        'DangerouslyNonSpecificScalar Type',
        'dangerously-non-specific-scalar'
    ],
    [
        'Dynamic Variables',
        'dynamic-variables'
    ],
    [
        'Restrict Field Directives to Specific Types',
        'restrict-field-directives-to-specific-types'
    ],
];
const displayUnlockPROPluginMessage = window.schemaConfigurationAdditionalDocumentationPro.displayUnlockPROPluginMessage;
const proPluginWebsiteURL = window.schemaConfigurationAdditionalDocumentationPro.proPluginWebsiteURL;
const title = displayUnlockPROPluginMessage
    ? __('[🔒] Additional Documentation', 'graphql-api')
    : __('[PRO] Additional Documentation', 'graphql-api');
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
                        className="graphql-api-info-modal-button text-wrap"
                    />
                )
            }
        </p>
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
