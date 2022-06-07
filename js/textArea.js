import { TextareaControl } from '@wordpress/components'
import { MainContext } from './app/context'
import { NewLine } from './components/newLine'

export const TextArea = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
	const args = field.hasOwnProperty('args') ? field.args : {}
	return (
		<>
		<NewLine field={ field } condition={ condition } />
		<div className='webaxones-field'>
			<TextareaControl key={ field.slug }
				className='webaxones-field__textarea'
				help={ field.hasOwnProperty('help') ? field.help : '' }
				label={ field.label }
				type={ 'number' }
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