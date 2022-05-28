import { MainContext } from './mainContext'
import { Text } from './text.js'
import { TextArea } from './textArea.js'
import { Checkbox } from './checkbox.js'
import { Toggle } from './toggle.js'
import { Image } from './image.js'
import { SelectDataScroll } from './selectDataScroll.js'
import { SelectData } from './selectData.js'

export const Field = ( { sectionId } ) => {
	const mainState = React.useContext( MainContext )

	console.log('mainState', mainState);
	return (
		<>
			{ mainState.fields.map( ( field, key ) => {

				if ( ( field.tab !== mainState.tabSelected )
					|| ( 'undefined' !== typeof sectionId && field.section !== sectionId )
					|| ( 'undefined' === typeof sectionId && '' !== field.section ) ) {
					return null
				}

				if ( [ 'text', 'number', 'datetime-local', 'email' ].includes( field.type ) ) return <Text key={ key } field={ field } />

				if ( 'textarea' === field.type ) return <TextArea key={ key } field={ field } />

				if ( 'checkbox' === field.type ) return <Checkbox key={ key } field={ field } />

				if ( 'toggle' === field.type ) return <Toggle key={ key } field={ field } />

				if ( 'image' === field.type ) return <Image key={ key } field={ field } />

				if ( 'selectDataScroll' === field.type ) return <SelectDataScroll key={ key } field={ field } />

				if ( 'selectData' === field.type ) return <SelectData key={ key } field={ field } />
			} ) }
		</>
	)
}