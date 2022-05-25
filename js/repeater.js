import { Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { Text } from './text.js'

export const Repeater = ( { parentFieldValue, parentField, parentOnChange } ) => {
	console.log('parentField', parentField);
	const onChangeField = () => {
		
	}
	return (
		<>
			<p className='wax-components-field__label'>{ parentField.label }</p>
			<div key={ parentField.id } className='wax-components-repeater'>
				{ parentField.children.map( ( subField, index ) => (
					<div key={ index } className={ 'wax-components-field' }>
						<Text fieldValue={ subField.value } field={ subField } onChange={ onChangeField } />
					</div>
				) ) }
			</div>
			<Button
            	className='button wax-components-repeater__field--add'
            	icon='insert'
				onChange={ ( value ) => {
					parentOnChange( value, parentField.id )
				} }
            	// onClick={ onAddRow }
            >
                { __( 'Add Row', 'webaxones-core' ) }
            </Button>
		</>
	)
}