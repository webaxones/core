import { TextareaControl } from '@wordpress/components'
import { MainContext } from './mainContext'

export const TextArea = ( { field } ) => {
	const mainState = React.useContext( MainContext )
	const args = field.hasOwnProperty('args') ? field.args : {}
	return (
		<div className='wax-components-field'>
			<TextareaControl key={ field.id }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				label={ field.label }
				type={ 'number' }
				value={ field.value || '' }
				{ ...args }
				onChange={ ( value ) => {
					mainState.onChange( value, field.id )
				} }
			/>
		</div>
	)
}