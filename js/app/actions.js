import { getNumberFromFieldSlug, initializeValue, findIndexToGo, isClone, isOnlyChild } from './helpers'

/**
 * Remove row of subfields from repeater fields
 *
 * @param {*} repeaterSlug
 * @param {*} fieldSlug
 * @param {*} mainState
 */
export const onRemoveRow = ( repeaterSlug, fieldSlug, mainState ) => {
	const nb = getNumberFromFieldSlug( fieldSlug )
	let  fieldsToRemove = []
	let repeater = mainState.fields.find( x => x.slug === repeaterSlug )
	mainState.fields.forEach( field => {
		if ( field.hasOwnProperty( 'repeater' ) && repeaterSlug === field.repeater
				&& ( field.slug.endsWith(`*${nb.toString()}`) || ( ! isClone( field.slug ) && ! isOnlyChild(  field.slug, mainState ) ) ) ) {
			fieldsToRemove.push( field.slug )
		}
	} )
	fieldsToRemove.forEach( slug => {
		mainState.fields.splice( mainState.fields.findIndex( a => a.slug === slug ) , 1)
		repeater.children.splice( repeater.children.findIndex( a => a.slug === slug ) , 1)
		repeater.value.splice( repeater.value.findIndex( a => a.slug === slug ) , 1)
	} )
}

/**
 * Move up or down row of subfields in repeater field
 *
 * @param {*} repeaterSlug
 * @param {*} fieldSlug
 * @param {*} mainState
 * @param {*} direction
 */
export const onMoveRow = ( repeaterSlug, fieldSlug, mainState, direction ) => {
	if ( ! [ 'up', 'down' ].includes( direction ) ) return

	const nb = getNumberFromFieldSlug( fieldSlug )
	let  fieldsToMove = []

	mainState.fields.forEach( field => {
		if ( field.slug.endsWith(`*${nb.toString()}`) && field.hasOwnProperty( 'repeater' ) && repeaterSlug === field.repeater ) {
			fieldsToMove.push( field.slug )
		}
		if ( 0 === nb && ! isClone( field.slug ) && field.hasOwnProperty( 'repeater' ) && repeaterSlug === field.repeater ) {
			fieldsToMove.push( field.slug )
		}
	} )

	let repeater = mainState.fields.find( x => x.slug === repeaterSlug )
	let indexToGo1 = 0
	let indexToGo2 = 0
	let indexToGo3 = 0
	let groupOfFields = []
	let groupOfChildren = []
	let groupOfValues = []

	fieldsToMove = fieldsToMove.reverse()

	fieldsToMove.forEach( slug => {
		indexToGo1 = 0 === indexToGo1 ? findIndexToGo( mainState.fields, fieldsToMove.length, slug, repeater, direction ) : indexToGo1
		groupOfFields.push( mainState.fields.splice( mainState.fields.findIndex( a => a.slug === slug ), 1 )[0] )

		indexToGo2 = 0 === indexToGo2 ? findIndexToGo( repeater.children, fieldsToMove.length, slug, repeater, direction ) : indexToGo2
		groupOfChildren.push( repeater.children.splice( repeater.children.findIndex( a => a.slug === slug ), 1 )[0] )

		indexToGo3 = 0 === indexToGo3 ? findIndexToGo( repeater.value, fieldsToMove.length, slug, repeater, direction ) : indexToGo3
		groupOfValues.push( repeater.value.splice( repeater.value.findIndex( a => a.slug === slug ), 1 )[0] )
	} )
	mainState.fields.splice( indexToGo1, 0, ...groupOfFields.reverse() )
	repeater.children.splice( indexToGo2, 0, ...groupOfChildren.reverse() )
	repeater.value.splice( indexToGo3, 0, ...groupOfValues.reverse() )
}

/**
 * Add row of subfields in repeater field
 *
 * @param {*} repeaterSlug
 * @param {*} mainState
 */
export const onAddRow = ( repeaterSlug, mainState ) => {
	let tempFields = []
	mainState.fields.forEach( field => {
		if ( repeaterSlug === field.slug ) {
			let nextNumber = 1
			field.children.forEach( child => {
				const nb = getNumberFromFieldSlug( child.slug )
				if (nb > nextNumber) nextNumber = nb
			} )
			nextNumber++

			const originalRepeater = mainState.currentPageGroups[0].find( x => x.slug === repeaterSlug )
			const originalChildren = 'undefined' === originalRepeater ? [] : originalRepeater.children
			let children = field.children.length === 0 ? originalChildren : field.children
			const tempChildren = []
			const tempValues = []
			let suffixNumber = ''
			children.forEach( child => {
				suffixNumber = '' === suffixNumber ? getNumberFromFieldSlug( child.slug ).toString() : suffixNumber
				if ( suffixNumber !== getNumberFromFieldSlug( child.slug ).toString() ) return
				let cloneChild = Object.assign( {}, child )
				const prefix = cloneChild.slug.includes( '*' ) ? cloneChild.slug.substring( 0, cloneChild.slug.indexOf( '*' ) ) : cloneChild.slug
				const originalField = originalRepeater.children.find( x => x.slug === prefix )
				if ( undefined === originalField ) return
				let cloneField = Object.assign( {}, originalField )
				cloneField.value = initializeValue( originalField )
				cloneField.section = false
				cloneField.repeater = originalRepeater.slug
				cloneField.slug = `${prefix}*${nextNumber}`
				tempFields.push( cloneField )
				cloneChild.slug = `${prefix}*${nextNumber}`
				cloneChild.value = initializeValue( child )
				tempChildren.push( cloneChild )
				tempValues.push( { slug: cloneChild.slug, value: cloneChild.value } )
			} )
			field.children.push( ...tempChildren )
			field.value.push( ...tempValues )
		}
	} )
	let indexToGo = 0
	const repeater = mainState.fields.find( x => x.slug === repeaterSlug )
	tempFields = tempFields.reverse()
	tempFields.forEach( tempField => {
		indexToGo = 0 === indexToGo ? findIndexToGo( mainState.fields, tempFields.length, tempField.slug, repeater, 'add' ) : indexToGo
		mainState.fields.splice( indexToGo, 0, tempField )
	} )
}