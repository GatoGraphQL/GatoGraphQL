/**
 * Application imports
 */
import SchemaConfigResponseHeadersCard from './schema-config-response-headers-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigResponseHeadersCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
