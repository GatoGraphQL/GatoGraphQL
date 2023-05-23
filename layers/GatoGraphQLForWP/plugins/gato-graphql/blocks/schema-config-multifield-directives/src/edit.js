/**
 * Application imports
 */
import SchemaConfigMultiFieldDirectivesCard from './schema-config-multifield-directives-card';
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigMultiFieldDirectivesCard
				{ ...props }
				getMarkdownContentCallback={ getModuleDocMarkdownContentOrUseDefault }
			/>
		</div>
	)
}

export default EditBlock;
