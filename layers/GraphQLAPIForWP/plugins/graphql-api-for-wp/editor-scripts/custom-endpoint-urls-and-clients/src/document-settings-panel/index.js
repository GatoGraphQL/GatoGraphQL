/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'custom-endpoint-urls-and-clients-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Custom Endpoint URLs and Clients', 'graphql-api') }
    >
        <p><strong>{ __('URLs:', 'graphql-api') }</strong></p>
        <p><strong>{ __('Clients:', 'graphql-api') }</strong></p>
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
