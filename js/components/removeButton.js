import { __ } from '@wordpress/i18n'
import { MainContext, RepeaterContext } from '../app/context'
import { Button } from '@wordpress/components'

export const RemoveButton = ( { fieldRepeater, fieldSlug } ) => {
	const mainState = React.useContext( MainContext )
	const repeaterState = React.useContext( RepeaterContext )
	return(
		<Button
			isSmall
			className='button wax-custom-settings__remove-row'
			icon='no-alt'
			label={ __( 'Remove Row', 'webaxones-core' ) }
			onClick={ () => {
				repeaterState.onRemoveRow( fieldRepeater, fieldSlug, mainState )
				mainState.onChange( mainState.fields.find( x => x.slug === fieldSlug ), fieldSlug )
			} }
		>
		</Button>
	)
}
