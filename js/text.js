import { TextControl } from '@wordpress/components'

export const Text = ( { fieldValue, field, onChange } ) => {

	return (
		<TextControl key={ field.id }
			help={ field.hasOwnProperty('help') ? field.help : '' }
			label={ field.label }
			type={ field.type }
			value={ fieldValue || '' }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
	)
}