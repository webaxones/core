import { TextControl } from '@wordpress/components'

export const Text = ( { fieldValue, field, onChange } ) => {
	const args = field.hasOwnProperty('args') ? field.args : {}
	return (
		<div className='wax-components-field'>
			<TextControl key={ field.id }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				label={ field.label }
				type={ field.type }
				value={ fieldValue || '' }
				{ ...args }
				onChange={ ( value ) => {
					onChange( value, field.id )
				} }
			/>
		</div>
	)
}