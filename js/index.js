import { render, useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import api from '@wordpress/api'
import { Button, TabPanel } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { MainContext } from './app/context'
import '../css/admin.scss'
import { Notices } from './components/notices'
import { Field } from './field'
import { initializeValue } from './app/helpers'

// Filter declarations dedicated to the current page
const objUrlParams    = new URLSearchParams( window.location.search )
const pageSlug        = objUrlParams.get( 'page' )
let currentPageGroups = webaxonesApps.filter( group => group[0].page === pageSlug )

console.log('currentPageGroups', currentPageGroups);

const App = () => {

	const [fields, setFields] = useState( [] )
	const [tabs, setTabs] = useState( [] )
	const [tabSelected, setTabSelected] = useState( currentPageGroups[0][0].tab )
  
	useEffect( () => {
		const getData = async () => {
			await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				settings.fetch().then( ( response ) => {
					let fields    = []
					let groups    = []

					currentPageGroups.forEach( group => {
						groups.push(
							{
								name: group[0].tab,
								title: group[0].tab_name,
								className: `${group[0].tab}__tab`,
							}
						)

						group.forEach( field => {
							const initValue = [ 'section', 'repeater' ].includes( field.type ) ? field.children.map( child => { return { slug: child.slug, value: initializeValue( child ) } } ) : initializeValue( field )
							fields.push(
								{
									slug: field.slug,
									label: field.label,
									help: field.help,
									value: null === response[ field.slug ] ? initValue : response[ field.slug ],
									tab: field.tab,
									section:false,
									repeater:false,
									type: field.type,
									args: field.args || {},
									children: field.children || {}
								}
							)

							let children = []

							if ( [ 'section', 'repeater' ].includes( field.type ) ) {
								if ( null !== response[ field.slug ] ) {
									field.children.forEach( child => {
										const responseChild = response[ field.slug ].find( x => x.slug === child.slug )
										if ( undefined === responseChild ) {
											children.push( { slug: child.slug, value: initializeValue( child ) } )
										} else {
											children.push( { slug: child.slug, value: responseChild.value } )
										}
									} )

								}
								if ( null === response[ field.slug ] ) {
									if ( undefined === field.value ) {
										children.push( ...field.children )
									}
								}

								let originalChilds = []

								children.forEach( (child, index ) => {
									if ( /\*[0-9]+$/.test( child.slug ) ) return
									originalChilds.push( field.children.find( x => x.slug === child.slug ) )
									originalChilds[ index ].value = child.value
								} )

								originalChilds.forEach( originalChild => {
									fields.push(
										{
											slug: originalChild.slug,
											label: originalChild.label,
											help: originalChild.help,
											value: originalChild.value,
											tab: originalChild.tab,
											section: 'section' === field.type ? field.slug : false,
											repeater: 'repeater' === field.type ? field.slug : false,
											type: originalChild.type,
											args: originalChild.args || {},
										}
									)
								} )

								if ( 'repeater' === field.type ) {
									children.forEach( child => {
										if ( /\*[0-9]+$/.test( child.slug ) ) {
											const originalField = fields.find( x => x.slug === child.slug.match( /.*(?=\*)/ )[0] )
											const subValue = null === response[ field.slug ] ? false : response[ field.slug ].find( x => x.slug === child.slug ).value
											const fieldToPush = {
												slug: child.slug,
												label: originalField.label,
												help: originalField.help,
												value: null === subValue ? false : subValue,
												tab: field.tab,
												section: false,
												repeater: field.slug,
												type: originalField.type,
												args: originalField.args || {},
											}
											fields.push( fieldToPush )
											field.children.push( fieldToPush )
										}
									} )
								}
							}
						} )
					} )
					setTabs( groups )
					setFields( fields )
				} )
			} )
		}
		getData()
	}, [] )

	const onChange = ( value, slug ) => {
		setFields( ( prevState ) => {
			return prevState.map( ( item ) => {
				if ( item.slug !== slug ) {
					return item
				}
				if ( item.repeater ) {
					const repeater = fields.find( x => x.slug === item.repeater )
					const valueToUpdate = repeater.value.find( x => x.slug === item.slug ) 
					valueToUpdate.value = value
				}
				if ( item.section ) {
					const section = fields.find( x => x.slug === item.section )
					const valueToUpdate = section.value.find( x => x.slug === item.slug ) 
					valueToUpdate.value = value
				}
				return {
					...item,
					value: value
				}
			} )
	  	} )
	}

	return (
		<MainContext.Provider value={ { onChange, fields, setFields, tabSelected, setTabSelected } }>
			<TabPanel tabs={ tabs } onSelect={ tab => setTabSelected( tab ) }>
				{ tab => { <>{ tab.children }</> } }
			</TabPanel>
			<div className='webaxones__container' style={ { paddingTop: 10 } }>
				<Field />
			</div>
			<div style={ { marginTop: 20 } }>
				<Button
					isPrimary
					onClick={ () => {
						const values = {}
						fields.forEach( field => {
							if ( [ 'section', 'repeater' ].includes( field.type ) ) {
								const children = []
								field.children.forEach( child => {
									const originalField = fields.find( x => x.slug === child.slug )
									children.push(
										{
											slug: child.slug,
											value: originalField.value,
										}
									)
								} )
								values[field.slug] = children
							}

							if ( ! [ 'section', 'repeater' ].includes( field.type ) ) {
								values[field.slug] = field.value
							}
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
			<div className="webaxones__notices"><Notices/></div>
		</MainContext.Provider>
	  )
}

document.addEventListener( 'DOMContentLoaded', () => {
    const htmlOutput = document.getElementById( 'webaxones-options' )
    if ( htmlOutput ) {
        render(
            <App />,
            htmlOutput
        )
    }
} )
