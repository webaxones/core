import { TextareaControl } from '@wordpress/components'

export const TextArea = ( { fieldValue, field, onChange } ) => {
	const args = field.hasOwnProperty('args') ? field.args : {}
	return (
		<TextareaControl key={ field.id }

			help={ field.hasOwnProperty('help') ? field.help : '' }
			label={ field.label }
			type={ 'number' }
			value={ fieldValue || '' }
			{ ...args }
			onChange={ ( value ) => {
				onChange( value, field.id )
			} }
		/>
	)
}