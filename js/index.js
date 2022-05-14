import { render, useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import api from '@wordpress/api'
import { Button, Icon, TabPanel, Panel, PanelBody, PanelRow, Placeholder, SelectControl, Spinner, ToggleControl } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { Text } from './text.js'
import { TextArea } from './textArea.js'
import { Notices } from './notices.js'

// Since 1 single variable send all declarations to JS, we filter those dedicated to the current page
const objUrlParams    = new URLSearchParams( window.location.search )
const pageSlug        = objUrlParams.get( 'page' )
let currentPageGroups = []
webaxonesApps.forEach( ( group ) => {
	if ( group[0].page === pageSlug ) currentPageGroups.push( group )
} )

const App = () => {

	const [fields, setFields] = useState( [] )
	const [tabs, setTabs] = useState( [] )
	const [tabSelected, setTabSelected] = useState( currentPageGroups[0][0].group )
  
	useEffect( () => {
		const getData = async () => {
			const response = await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				const settingsGroup = settings.fetch().then( ( response ) => {
					const data = []
					currentPageGroups.forEach( group => {
						group.forEach( field => {
							data.push(
								{
									id: field.slug,
									label: field.label,
									help: field.help,
									value: response[ field.slug ],
									tab: field.group,
									type: field.type
								}
							)
						} )
					} )
					setFields(data)
				} )
			} )
		}
		getData()
	}, [] )

	useEffect( () => {
		const getTabs = async () => {
			const response = await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				const settingsGroup = settings.fetch().then( ( response ) => {
					const data = []
					currentPageGroups.forEach( group => {
						data.push(
							{
								name: group[0].group,
								title: group[0].group_name,
								className: `${group[0].group}__tab`,
							}
						)
					} )
					setTabs( data )
				} )
			} )
		}
		getTabs()
	}, [] )

	const onChangeField = ( value, id ) => {
		setFields( ( prevState ) => {
			return prevState.map( ( item ) => {
				if ( item.id !== id ) {
					return item
				}
				return {
					...item,
					value: value
				}
			} )
	  	} )
	}

	return (
		<>
			<TabPanel tabs={ tabs } onSelect={ ( tab ) => setTabSelected( tab ) }>
				{ ( tab ) => <>{ tab.children }</> }
			</TabPanel>
			<div style={ {paddingTop: 10} }>
				{ fields.map( ( field, key ) => {
					if ( field.tab !== tabSelected ) {
						return null
					}
					if ( 'text' === field.type || 'number' === field.type ) {
						return <div key={ key } style={ {marginTop: 10} }><Text fieldValue={ field.value } field={ field } onChange={ onChangeField } /></div>
					}
					if ( 'textarea' === field.type ) {
						return <div key={ key } style={ {marginTop: 10} }><TextArea fieldValue={ field.value } field={ field } onChange={ onChangeField } /></div>
					}
				} ) }
			</div>
			<div style={ {marginTop: 20} }>
				<Button
					isPrimary
					onClick={ () => {
						const values = {}

						fields.forEach( field => {
							values[field.id] = field.value
						} )

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
			</div>

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
        )
    }
} )
