import { Panel, PanelBody, PanelRow } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { Text } from './text.js'
import { MainContext } from './mainContext'

export const Section = ( { parentFieldValue, parentField, onChange } ) => {
	return (
		<Panel>
			<PanelBody title={ parentField.label } initialOpen={ true }>
				<PanelRow>
					{ parentField.children.map( ( subField, index ) => (
						<div key={ index } className={ 'wax-components-field' }>
							<Text fieldValue={ subField.value } field={ subField } onChange={ onChange } />
						</div>
					) ) }
				</PanelRow>
			</PanelBody>
		</Panel>
	)
}