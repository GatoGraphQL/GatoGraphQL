/**
 * Application imports
 */
import SchemaConfigExposeAdminDataCard from './schema-config-expose-admin-data-card';
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigExposeAdminDataCard
				{ ...props }
				getMarkdownContentCallback={ getModuleDocMarkdownContentOrUseDefault }
			/>
		</div>
	)
}

export default EditBlock;
