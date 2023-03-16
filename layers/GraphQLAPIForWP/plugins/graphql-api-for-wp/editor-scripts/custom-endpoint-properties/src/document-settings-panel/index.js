/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import CustomEndpointProperties from './custom-endpoint-properties.js';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'custom-endpoint-properties-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Custom Endpoint Properties', 'graphql-api') }
    >
        <CustomEndpointProperties />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
