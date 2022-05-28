import { render, useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import api from '@wordpress/api'
import { Button, TabPanel, Panel, PanelBody, PanelRow } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { MainContext } from './mainContext'
import '../css/admin.scss'
import { Notices } from './notices.js'
import { Field } from './field.js'

// Filter declarations dedicated to the current page
const objUrlParams    = new URLSearchParams( window.location.search )
const pageSlug        = objUrlParams.get( 'page' )
let currentPageGroups = webaxonesApps.filter( group => group[0].page === pageSlug )

const App = () => {

	const [fields, setFields] = useState( [] )
	const [tabs, setTabs] = useState( [] )
	const [sections, setSections] = useState( [] )
	const [tabSelected, setTabSelected] = useState( currentPageGroups[0][0].group )
  
	useEffect( () => {
		const getData = async () => {
			await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				settings.fetch().then( ( response ) => {
					let fields   = []
					let groups   = []
					let sections = []
					currentPageGroups.forEach( group => {
						groups.push(
							{
								name: group[0].group,
								title: group[0].group_name,
								className: `${group[0].group}__tab`,
							}
						)
						group.forEach( field => {
							( 'section' === field.type ) && sections.push(
								{
									id: field.slug,
									tab: field.group,
									label: field.label,
									children: field.children || {}
								}
							) && field.children.forEach( child => {
								fields.push(
									{
										id: child.slug,
										label: child.label,
										help: child.help,
										value: null === response[ child.slug ] ? false : response[ child.slug ],
										tab: child.group,
										section:field.slug,
										type: child.type,
										args: child.args || {},
										children: {}
									}
								)
							} )

							! ( 'section' === field.type ) && fields.push(
								{
									id: field.slug,
									label: field.label,
									help: field.help,
									value: null === response[ field.slug ] ? false : response[ field.slug ],
									tab: field.group,
									section: '',
									type: field.type,
									args: field.args || {},
									children: field.children || {}
								}
							)
						} )
					} )
					setTabs( groups )
					setSections( sections )
					setFields( fields )
				} )
			} )
		}
		getData()
	}, [] )

	const onChange = ( value, id ) => {
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
		<MainContext.Provider value={ { onChange, fields, tabSelected, setTabSelected, sections } }>
			<TabPanel tabs={ tabs } onSelect={ tab => setTabSelected( tab ) }>
				{ tab => {<>{ tab.children }</>} }
			</TabPanel>
			{ sections.map( ( section, key ) => {
				return (
					<div key={ key } className='wax-custom-settings__container' style={ { paddingTop: 10 } }>
						<Panel>
							<PanelBody title={ section.label } initialOpen={ true }>
								<PanelRow>
									<Field sectionId={ section.id } />
								</PanelRow>
							</PanelBody>
						</Panel>
					</div>
				)
			} ) }
			<div className='wax-custom-settings__container' style={ { paddingTop: 10 } }>
				<Field />
			</div>
			<div style={ { marginTop: 20 } }>
				<Button
					isPrimary
					onClick={ () => {
						const values = {}
						fields.forEach( field => {
							values[field.id] = field.value
						} )

						const settings = new api.models.Settings( values )
						settings.save()

						dispatch('core/notices').createNotice( 'success',
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

			<div className="wax-custom-settings__notices"><Notices/></div>
		</MainContext.Provider>
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
