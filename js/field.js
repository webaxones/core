import { __ } from '@wordpress/i18n'
import { MainContext, RepeaterContext } from './app/context'
import { Text } from './text'
import { TextArea } from './textArea'
import { Checkbox } from './checkbox'
import { Toggle } from './toggle'
import { Image } from './image'
import { SelectDataScroll } from './selectDataScroll'
import { SelectData } from './selectData'
import { Panel, PanelBody, PanelRow } from '@wordpress/components'
import { getNumberFromFieldSlug } from './app/helpers'
import { onAddRow, onRemoveRow } from './app/actions'
import { AddButton } from './components/addButton'

let previousCounter = 0
let actualCounter = 0
let isNewLine = false

export const Field = ( { wrapperSlug } ) => {
	const mainState = React.useContext( MainContext )
	console.log('mainState.fields',mainState.fields);

	return (
		<RepeaterContext.Provider value={ { onAddRow, onRemoveRow } }>
		<>
			{ mainState.fields.map( ( field, key ) => {

				if ( field.tab !== mainState.tabSelected ) return null

				if( 'undefined' !== typeof wrapperSlug ) {
					if ( ! field.section && ! field.repeater ) return null
					if ( field.section && field.section !== wrapperSlug ) return null
					if ( field.repeater && field.repeater !== wrapperSlug ) return null
				}

				if( 'undefined' === typeof wrapperSlug ) {
					if ( field.section ) return null
					if ( field.repeater ) return null
				}

				if ( 'section' === field.type ) {
					return (
						<div key={ key } className='wax-custom-settings__container wax-custom-settings__section' style={ { paddingTop: 10 } }>
							<Panel>
								<PanelBody title={ field.label } initialOpen={ true }>
									<PanelRow>
										<Field wrapperSlug={ field.slug } />
									</PanelRow>
								</PanelBody>
							</Panel>
						</div>
					)
				}

				if ( 'repeater' === field.type ) {
					return(
						<div key={ key } className='wax-custom-settings__container wax-custom-settings__repeater' style={ { paddingTop: 10 } }>
							{
								(
									<Panel>
										<PanelBody title={ field.label } initialOpen={ true }>
											<PanelRow>
												<div className='wax-components-field__repeater'>
													<Field wrapperSlug={ field.slug } />
												</div>
											</PanelRow>
											<AddButton fieldRepeater={ field } />
										</PanelBody>
									</Panel>
								)
							}
						</div>
					)
				}

				if ( field.hasOwnProperty( 'repeater' ) && field.repeater && /\*[0-9]+$/.test( field.slug ) ) {
					actualCounter = getNumberFromFieldSlug( field.slug )
					isNewLine = actualCounter > previousCounter ? true : false
					previousCounter = actualCounter
				}
				if ( field.hasOwnProperty( 'repeater' ) && field.repeater && ! /\*[0-9]+$/.test( field.slug ) ) {
					actualCounter = 1
					previousCounter = 1
					isNewLine = false
				}

				switch ( field.type ) {
					case 'text':
					case 'number':
					case 'datetime-local':
					case 'email':
						return <Text key={ key } field={ field } condition={ isNewLine } />
					case 'textarea':
						return <TextArea key={ key } field={ field } />
					case 'checkbox':
						return <Checkbox key={ key } field={ field } condition={ isNewLine } />
					case 'toggle':
						return <Toggle key={ key } field={ field } condition={ isNewLine } />
					case 'image':
						return <Image key={ key } field={ field } condition={ isNewLine } />
					case 'selectDataScroll':
						return <SelectDataScroll key={ key } field={ field } condition={ isNewLine } />
					case 'selectData':
						return <SelectData key={ key } field={ field } condition={ isNewLine } />
					default:
						return null
				}
			} ) }
		</>
		</RepeaterContext.Provider>
	)
}