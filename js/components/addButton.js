import { __ } from '@wordpress/i18n'
import { MainContext, RepeaterContext } from '../app/context'
import { Button } from '@wordpress/components'
import { getBiggestNumber } from '../app/helpers'

export const AddButton = ( { fieldRepeater } ) => {
	const mainState = React.useContext( MainContext )
	const repeaterState = React.useContext( RepeaterContext )
	return(
		<Button
			isSmall
			className='button webaxones-field__repeater__add-row'
			onClick={ () => {
				repeaterState.onAddRow( fieldRepeater.slug, mainState )
				if ( fieldRepeater.children.length > 1 ) {
					const childToChange = fieldRepeater.children.find( x => x.slug === fieldRepeater.children[0].slug + '*' + getBiggestNumber( fieldRepeater.children ) )
					mainState.onChange( childToChange.value, childToChange.slug )
				} else {
					mainState.onChange( mainState.fields.find( x => x.slug === fieldRepeater.slug ), fieldRepeater.slug )
				}
			} }
		>
			{ __( 'Add Row', 'webaxones-core' ) }
		</Button>
	)
}