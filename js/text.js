import { TextControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { MainContext } from './app/context'
import { NewLine } from './components/newLine'

export const Text = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
	const args = field.hasOwnProperty('args') ? field.args : {}
	return (
		<>
		<NewLine field={ field } condition={ condition } />
		<div className='wax-components-field'>
			<TextControl key={ field.slug }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				label={ field.label }
				type={ field.type }
				value={ field.value || '' }
				{ ...args }
				onChange={ ( value ) => {
					mainState.onChange( value, field.slug )
				} }
			/>
		</div>
		</>
	)
}