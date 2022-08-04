/**
 * Get number from field slug:
 * Each clone of a repeater's subfield (a clone is a repeated subfield) ends with a suffix
 * consisting of * and an index corresponding to the number of existing clones (the gaps are not filled)
 * Exemple: if 'user_name' is cloned 2 times, slugs will be 'user_name', 'user_name*2', 'user_name*3'
 * This function gets the index from the field slug
 * If there is no index, 0 is returned
 *
 * @param {*} fieldSlug slug of the field
 * @return {*} 
 */
 export const getNumberFromFieldSlug = fieldSlug => {
	const key = null !== fieldSlug.match( /\*[0-9]+$/ ) ? fieldSlug.match( /\*[0-9]+$/ )[0] : ''
	const nb = parseInt( key.substring(key.indexOf('*') + 1 ), 10 ) || 0
	return nb
}

/**
 * Check if repeater's subfield is a clone or not
 *
 * @param {*} fieldSlug slug of the field
 * @return {*} 
 */
 export const isClone = fieldSlug => {
	return null === fieldSlug.match( /\*[0-9]+$/ ) ? false : true
}

/**
 * Check if repeater's subfield is the first child or not
 *
 * @param {*} fieldSlug slug of the field
 * @param {*} mainState value of the main state context
 * @return {*} 
 */
 export const isFirstChild = ( fieldSlug, mainState ) => {
	const field = mainState.fields.find( x => x.slug === fieldSlug )
	const repeater = mainState.fields.find( x => x.slug === field.repeater )
	const firstChild = repeater.children[ 0 ]
	return getNumberFromFieldSlug( field.slug ) === getNumberFromFieldSlug( firstChild.slug ) ? true : false
}

/**
 * Check if repeater's subfield is the last child or not
 *
 * @param {*} fieldSlug slug of the field
 * @param {*} mainState value of the main state context
 * @return {*} 
 */
export const isLastChild = ( fieldSlug, mainState ) => {
	const field = mainState.fields.find( x => x.slug === fieldSlug )
	const repeater = mainState.fields.find( x => x.slug === field.repeater )
	const lastChild = repeater.children[ repeater.children.length - 1 ]
	return getNumberFromFieldSlug( field.slug ) === getNumberFromFieldSlug( lastChild.slug ) ? true : false
}

/**
 * Check if repeater's subfield is the only child or not
 *
 * @param {*} fieldSlug slug of the field
 * @param {*} mainState value of the main state context
 * @return {*} 
 */
export const isOnlyChild = ( fieldSlug, mainState ) => {
	const field = mainState.fields.find( x => x.slug === fieldSlug )
	const repeater = mainState.fields.find( x => x.slug === field.repeater )
	const firstChildNumber = getNumberFromFieldSlug( repeater.children[0].slug ).toString()
	const suffix = '0' === firstChildNumber ? '' : `*${firstChildNumber}`
	const siblings = repeater.children.filter( child => child.slug.endsWith( suffix ) )
	if ( siblings.length === repeater.children.length ) return true
	return false
}

/**
 * Get the biggest index from repeater's children
 * See 'getNumberFromFieldSlug' documentation for more explanations
 *
 * @param {*} repeaterChildren repeater's children (subfields)
 * @return {*} 
 */
 export const getBiggestNumber = repeaterChildren => {
	let number = 1
	repeaterChildren.forEach( child => {
		const nb = getNumberFromFieldSlug( child.slug )
		if (nb > number) number = nb
	} )
	return number.toString()
}

/**
 * Initialize value depending on field's type
 *
 * @param {*} field
 * @return {*} 
 */
 export const initializeValue = field => {
	if ( ! field.hasOwnProperty( 'type' ) ) return ''
	switch ( field.type ) {
		case 'text':
		case 'textarea':
		case 'number':
		case 'datetime-local':
		case 'email':
			return '';

		case 'checkbox':
		case 'toggle':
			return false;

		case 'image':
			return {
				slug: '',
				url: '',
			};

		case 'selectData':
		case 'selectDataScroll':
			return false;
		
		case 'section':
		case 'repeater':
			return [];

		default:
			return '';
	}
}
/**
 * Get difference between 2 arrays
 *
 * @param {*} array1
 * @param {*} array2
 * @return {*} 
 */
export const getDifference = ( array1, array2 ) => {
	return array1.filter(object1 => {
		return ! array2.some( object2 => {
			return object1.slug === object2.slug
		} )
	} )
}

/**
 * Find position of child in repeater's children
 *
 * @param {*} childrenOrdered
 * @param {*} slugItem
 * @return {*} 
 */
export const findPosition = ( childrenOrdered, slugItem ) => {
	const index = childrenOrdered.findIndex( object => {
		return object.slug === slugItem
	} )
	if ( -1 === index ) return 0
	return index
}

export const findIndexToGo = ( arrayOfFields, nbFieldsToMove, fieldSlug, repeater, direction ) => {
	const fieldEnd = getNumberFromFieldSlug( fieldSlug )
	const currentIndexLastfield = arrayOfFields.findIndex( a => a.slug === fieldSlug )
	const currentIndexFirstfield = currentIndexLastfield - ( nbFieldsToMove - 1 )
	if ( undefined === repeater ) return

	if ( 'up' === direction ) {
		for ( let index = currentIndexLastfield; index > 0; index-- ) {
			const previousField = arrayOfFields[ index ]
			const previousFieldEnd = getNumberFromFieldSlug( previousField.slug )
			if ( fieldEnd !== previousFieldEnd ) {
				return ( index - ( nbFieldsToMove - 1 ) )
			}
		}
	}

	if ( 'down' === direction ) {
		for ( let index = currentIndexFirstfield; index < arrayOfFields.length; index++ ) {
			const nextField = arrayOfFields[ index ]
			const nextFieldEnd = getNumberFromFieldSlug( nextField.slug )
			if ( fieldEnd !== nextFieldEnd ) return index
		}
	}

	if ( 'add' === direction ) {
		if ( 1 === arrayOfFields.length ) {
			return 1
		}
		return arrayOfFields.length
	}
}