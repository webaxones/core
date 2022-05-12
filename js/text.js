import { TextControl } from '@wordpress/components'

export const Text = ( { state, field, onChange } ) => {
	return (
		<TextControl key={ field.slug }
			help={ field.labels.hasOwnProperty('help') ? field.labels.help : '' }
			label={ field.labels.label }
			value={ state[field.slug] || '' }
			onChange={ (value) => {
				onChange( field.slug, value )
			} }
		/>
	)
}