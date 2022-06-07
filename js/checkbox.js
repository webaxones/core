import { CheckboxControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { MainContext } from './app/context'
import { NewLine } from './components/newLine'

export const Checkbox = ( { field, condition } ) => {
	const mainState = React.useContext( MainContext )
	return (
		<>
		<NewLine field={ field } condition={ condition } />
		<div className='webaxones-field'>
			<CheckboxControl key={ field.slug }
			 	className='webaxones-field__checkbox'
				label={ field.label }
				help={ field.hasOwnProperty('help') ? field.help : '' }
				checked={ field.value || false }
				onChange={ ( value ) => {
					mainState.onChange( value, field.slug )
				} }
			/>
		</div>
		</>
	)
}
