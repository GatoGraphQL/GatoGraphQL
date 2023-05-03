/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import CustomEndpointOverview from './custom-endpoint-overview.js';

/**
 * Constants to customize
 */
const DOCUMENT_SETTINGS_PANEL_NAME = 'custom-endpoint-overview-panel';

const DocumentSettingsPanel = () => (
    <PluginDocumentSettingPanel
        name={ DOCUMENT_SETTINGS_PANEL_NAME }
        title={ __('Custom Endpoint Overview', 'graphql-api') }
    >
        <CustomEndpointOverview />
    </PluginDocumentSettingPanel>
);
export default DocumentSettingsPanel;
export { DOCUMENT_SETTINGS_PANEL_NAME };
