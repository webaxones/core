import { TextControl } from '@wordpress/components'
import { MainContext } from './mainContext'

export const Text = ( { field } ) => {
	const mainState = React.useContext( MainContext )
	const args = field.hasOwnProperty('args') ? field.args : {}
	return (
		<div className='wax-components-field'>
			<TextControl key={ field.id }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				label={ field.label }
				type={ field.type }
				value={ field.value || '' }
				{ ...args }
				onChange={ ( value ) => {
					mainState.onChange( value, field.id )
				} }
			/>
		</div>
	)
}