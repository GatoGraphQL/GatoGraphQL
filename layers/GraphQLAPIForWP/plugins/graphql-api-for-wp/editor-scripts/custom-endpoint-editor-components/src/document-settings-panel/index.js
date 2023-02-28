/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import { MarkdownGuideButton } from '@graphqlapi/components';
import { getMarkdownContentOrUseDefault } from '../markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'endpoint-document-settings-panel';
const guideName = __('Creating Custom Endpoints', 'graphql-api');
const pageFilenames = [
    'welcome-guide',
    'schema-config-options',
]
/**
 * Component
 */
const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Welcome Guide', 'graphql-api') }
    >
        <MarkdownGuideButton
            getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
            contentLabel={ guideName }
            guideName={ guideName }
            pageFilenames={ pageFilenames }
        />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
