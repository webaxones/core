import { getNumberFromFieldSlug, initializeValue } from './helpers'

export const onRemoveRow = ( repeaterSlug, fieldSlug, mainState ) => {
	const nb = getNumberFromFieldSlug( fieldSlug )
	let  fieldsToRemove = []
	mainState.fields.forEach( field => {
		if ( field.slug.endsWith(`*${nb.toString()}`) && field.hasOwnProperty( 'repeater' ) && repeaterSlug === field.repeater ) {
			fieldsToRemove.push( field.slug )
		}
	} )
	let repeater = mainState.fields.find( x => x.slug === repeaterSlug )
	fieldsToRemove.forEach( slug => {
		mainState.fields.splice( mainState.fields.findIndex( a => a.slug === slug ) , 1)
		repeater.children.splice( repeater.children.findIndex( a => a.slug === slug ) , 1)
		repeater.value.splice( repeater.value.findIndex( a => a.slug === slug ) , 1)
	} )
}

export const onAddRow = ( repeaterSlug, mainState ) => {
	const tempFields = []
	mainState.fields.forEach( field => {
		if ( repeaterSlug === field.slug ) {
			let nextNumber = 1
			field.children.forEach( child => {
				const nb = getNumberFromFieldSlug( child.slug )
				if (nb > nextNumber) nextNumber = nb
			} )
			nextNumber++

			const tempChildren = []

			field.children.forEach( child => {
				if ( /\*[0-9]+$/.test( child.slug ) ) return
				let cloneChild = Object.assign( {}, child )
				const originalField = mainState.fields.find( x => x.slug === cloneChild.slug )
				if ( 'undefined' === originalField ) return
				let cloneField = Object.assign( {}, originalField )
				cloneField.value = initializeValue( originalField )
				cloneChild.slug = `${cloneChild.slug}*${nextNumber}`
				cloneField.slug = cloneChild.slug
				tempChildren.push( cloneChild )
				tempFields.push( cloneField )
			} )
			field.children.push( ...tempChildren )
			
			tempFields.forEach( tempField => {
				field.value.push( { slug: tempField.slug, value: tempField.value } )
			} )
		}
	} )
	mainState.fields.push( ...tempFields )
}