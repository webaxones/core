import { render, useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import api from '@wordpress/api'
import { Button, Icon, TabPanel, Panel, PanelBody, PanelRow, Placeholder, SelectControl, Spinner, TextControl, ToggleControl } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { Text } from './text.js'
import { Notices } from './notices.js'

const settingsGroup = webaxonesApps[0]

const onSelect = ( tabName ) => {
	console.log( 'Selecting tab', tabName );
}

const App = () => {

		const [isAPILoaded, setAPILoaded] = useState( false )

		const [fields, setFields] = useState( { slug: '', value: '' } )

		const handleOnChange = ( slug, value ) => {
			setFields( prevState => ( { ...prevState, [slug]: value } ) )
		}

		const [theTabs] = useState( {
			tabs: `[
				{
					name: 'tab1',
					title: 'Tab 1',
					className: 'tab-one',
					children: '<p>Toto</p>'
				},
				{
					name: 'tab2',
					title: 'Tab 2',
					className: 'tab-two',
					children: '<p>Tata</p>'
				},
			]`
		} )

		const setTabs = () => {
			const toto = {
				name: 'tab1',
				title: 'Tab 1',
				className: 'tab-one',
				children: 'Toto'
			}
			return toto
		}

		useEffect( () => {
			api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				settings.fetch().then( ( response ) => {
					webaxonesApps.forEach( settingsGroup => {
						settingsGroup.fields.forEach( field => {
							setFields( prevState => ( { ...prevState, [field.slug]: response[ field.slug ] } ) )
						} )
					} )
				} )
			} )
		}, [] );

		return (
			<>
			{
				( webaxonesApps.length === 1 ) 
					? webaxonesApps[0].fields.map( field => {
 						return <Text key={ field.slug } fieldValue={ fields[field.slug] } field={ field } onChange={ handleOnChange } />
					} )
					: <TabPanel
							key={`${webaxonesApps[0]['page_slug']}`}
							className={ `${webaxonesApps[0]['page_slug']}__panel` }
							activeClass='active-tab'
							onSelect={ onSelect }
							tabs={ [ setTabs ] }
					>
					{ ( tab ) => <div>{ tab.children }</div> }
					</TabPanel>
			}

				<Button
					isPrimary
					onClick={ () => {
						const values = fields
						delete values.isAPILoaded

						const settings = new api.models.Settings( values )
						settings.save()

						dispatch('core/notices').createNotice(
							'success',
							__( 'Settings Saved', 'webaxones-core' ),
							{
								type: 'snackbar',
								isDismissible: true,
							}
						)
					} }
				>
					{ __( 'Save', 'webaxones-core' ) }
				</Button>

				<div className="wax-company-settings__notices">
					<Notices/>
				</div>
			</>
		)
}

document.addEventListener( 'DOMContentLoaded', () => {
    const htmlOutput = document.getElementById( 'wax-company-settings__content' )
    if ( htmlOutput ) {
        render(
            <App />,
            htmlOutput
        );
    }
});
