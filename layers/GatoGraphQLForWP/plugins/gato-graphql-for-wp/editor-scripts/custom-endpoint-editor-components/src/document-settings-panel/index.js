/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import { MarkdownGuideButton } from '@gatographql/components';
import { getMarkdownContentOrUseDefault } from '../markdown-loader';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'custom-endpoint-document-settings-panel';
const guideName = __('Creating Custom Endpoints', 'gato-graphql');
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
        title={ __('Welcome Guide', 'gato-graphql') }
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
