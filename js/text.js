import { TextControl } from '@wordpress/components'

export const Text = ( { fieldValue, field, onChange } ) => {

	return (
		<TextControl key={ field.slug }
			help={ field.labels.hasOwnProperty('help') ? field.labels.help : '' }
			label={ field.labels.label }
			value={ fieldValue || '' }
			onChange={ (value) => {
				onChange( field.slug, value )
			} }
		/>
	)
}