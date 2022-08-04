import { render, useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import api from '@wordpress/api'
import { Button, TabPanel } from '@wordpress/components'
import { dispatch } from '@wordpress/data'
import { MainContext } from './app/context'
import '../css/admin.scss'
import { Notices } from './components/notices'
import { Field } from './field'
import { initializeValue, getDifference, getBiggestNumber, findPosition, isClone } from './app/helpers'

// Filter declarations dedicated to the current page
const objUrlParams    = new URLSearchParams( window.location.search )
const pageSlug        = objUrlParams.get( 'page' )
let currentPageGroups = webaxonesApps.filter( group => group[0].page === pageSlug )

const App = () => {

	const [fields, setFields] = useState( [] )
	const [tabs, setTabs] = useState( [] )
	const [tabSelected, setTabSelected] = useState( currentPageGroups[0][0].tab )
  
	useEffect( () => {
		const getData = async () => {
			await api.loadPromise.then( () => {
				const settings = new api.models.Settings()
				settings.fetch().then( ( response ) => {
					let actualFields    = []
					let groups    = []

					currentPageGroups.forEach( group => {

						// Populates groups to build tabs
						groups.push(
							{
								name: group[0].tab,
								title: group[0].tab_name,
								className: `${group[0].tab}__tab`,
							}
						)

						group.forEach( field => {

							// Initialize value depending on field type
							let initValue = []
							let initChildrenValues = []
							let fieldChildren = []

							if ( ! [ 'section', 'repeater' ].includes( field.type ) ) {
								initValue = initializeValue( field )
							}

							if ( [ 'section', 'repeater' ].includes( field.type ) ) {
								initValue = field.children.map( child => { return { slug: child.slug, value: initializeValue( child ) } } )
								initChildrenValues = field.children.map( child => { 
									let newChild = Object.assign({}, child )
									newChild.value = initializeValue( newChild )
									return newChild
								} )

								if ( null !== response[ field.slug ] ) {
									field.children.forEach( child => { 
										if ( undefined !== response[ field.slug ].find( x => x.slug === child.slug ) ) {
											fieldChildren.push( child )
										}
									} )
								}
							}

							// Populates 'actualFields' to build fields
							actualFields.push(
								{
									slug: field.slug,
									label: field.label,
									help: field.help,
									value: ( undefined === response[ field.slug ] || null === response[ field.slug ] ) ? initValue : response[ field.slug ],
									tab: field.tab,
									section:false,
									repeater:false,
									type: field.type,
									args: field.args || {},
									children: ( undefined !== response[ field.slug ] && null !== response[ field.slug ] ) ? fieldChildren : ( initChildrenValues || [] )
								}
							)

							// Duplicates the section and repeater subfields as root fields of mainState.fields so that they are displayed
							let children = []

							if ( [ 'section', 'repeater' ].includes( field.type ) ) {

								// Populates 'childrenInRightOrder' to maintain the correct order of subfields 
								const childrenInRightOrder = []
								childrenInRightOrder.push( ...initChildrenValues )

								// Populates 'children' which will temporarily contain all the children in order to make them fields
								if ( undefined !== response[ field.slug ] && null !== response[ field.slug ] ) {
									response[ field.slug ].forEach( child => {
										if ( undefined === child.value ) {
											children.push( { slug: child.slug, value: initializeValue( child ) } )
										} else {
											children.push( { slug: child.slug, value: child.value } )
										}
									} )
								}
								if ( ( undefined === response[ field.slug ] || null === response[ field.slug ] ) && undefined === field.value ) {
									children.push( ...initChildrenValues )
								}

								// If subfields are added after content has been added
								let childrenAddedLater = []
								if ( undefined !== response[ field.slug ] && null !== response[ field.slug ] ) {
									childrenAddedLater = getDifference( initChildrenValues, response[ field.slug ] ).filter( el => isClone( el.slug ) )
								}
								if ( childrenAddedLater.length > 0 ) {
									const biggestNumber = getBiggestNumber( response[ field.slug ] )
									childrenAddedLater.forEach( childAddedLater => {
										if ( ! isClone( childAddedLater.slug ) ) return
										let previousChildSlug = ''
										for ( let index = 1; index <= biggestNumber; index ++ ) {
											const fieldToPush = {
												slug: 1 === index ? childAddedLater.slug : `${childAddedLater.slug}*${index}`,
												value: initializeValue( childAddedLater ),
											}
											if ( '' === previousChildSlug ) {
												let initialPosition = findPosition( childrenInRightOrder, childAddedLater.slug )
												previousChildSlug = initialPosition > 0 ? children[ initialPosition -1 ].slug : childAddedLater.slug
											}
											let position = 0
											if ( index > 1 ) {
												position = findPosition( children, `${previousChildSlug}*${index}` ) + 1
											} else {
												position = findPosition( childrenInRightOrder, childAddedLater.slug )
											}
											children.splice( position, 0, fieldToPush )
										}
									} )
								}

								// Populates 'originalChilds' which will be used to set Fields
								let originalChilds = []
								children.forEach( child => {
									if ( /\*[0-9]+$/.test( child.slug ) ) return
									originalChilds.push( initChildrenValues.find( x => x.slug === child.slug ) )
									originalChilds[ originalChilds.length - 1 ].value = child.value
								} )

								if ( 'section' === field.type ) {
									originalChilds.forEach( originalChild => {
										actualFields.push(
											{
												slug: originalChild.slug,
												label: originalChild.label,
												help: originalChild.help,
												value: originalChild.value,
												tab: originalChild.tab,
												section: field.slug,
												repeater: false,
												type: originalChild.type,
												args: originalChild.args || {},
											}
										)
									} )
								}
								if ( 'repeater' === field.type ) {
									actualFields.find( x => x.slug === field.slug ).children = []
									children.forEach( child => {
										let originalField = {}
										if ( isClone( child.slug ) ) {
											const originalRepeater = currentPageGroups[0].find( x => x.slug === field.slug )
											originalField = originalRepeater.children.find( x => x.slug === child.slug.match( /.*(?=\*)/ )[0] )
										}
										if ( ! isClone( child.slug ) ) {
											originalField = originalChilds.find( x => x.slug === child.slug )
										}

										let subValue = false
										if ( null !== response[ field.slug ] && undefined !== response[ field.slug ] ) {
											subValue = undefined !== response[ field.slug ].find( x => x.slug === child.slug ) ? response[ field.slug ].find( x => x.slug === child.slug ).value : false
										}
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
										actualFields.push( fieldToPush )
										actualFields.find( x => x.slug === field.slug ).children.push( fieldToPush )
									} )
								}
							}
						} )
					} )
					setTabs( groups )
					setFields( actualFields )
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
		<MainContext.Provider value={ { currentPageGroups, onChange, fields, setFields, tabSelected, setTabSelected } }>
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
									let value = ''
									if ( undefined === originalField ) {
										value = initializeValue( child )
									}
									if ( undefined !== originalField ) {
										value = undefined === originalField.value ? initializeValue( originalField ) : originalField.value
									}
									children.push(
										{
											slug: child.slug,
											value: value,
										}
									)
								} )
								values[field.slug] = children
							}

							if ( ! [ 'section', 'repeater' ].includes( field.type ) && false === field.section && false === field.repeater ) {
								const value = undefined === field.value ? initializeValue( field ) : field.value
								values[field.slug] = value
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
