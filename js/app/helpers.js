export const getNumberFromFieldSlug = ( fieldSlug ) => {
	const key = null !== fieldSlug.match( /\*[0-9]+$/ ) ? fieldSlug.match( /\*[0-9]+$/ )[0] : ''
	const nb = parseInt( key.substring(key.indexOf('*') + 1 ), 10 ) || 0
	return nb
}
export const getBiggestNumber = ( repeater ) => {
	let number = 1
	repeater.children.forEach( child => {
		const nb = getNumberFromFieldSlug( child.slug )
		if (nb > number) number = nb
	} )
	return number.toString()
}

export const initializeValue = ( field ) => {
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
			return {};
		
		case 'section':
		case 'repeater':
			return [];

		default:
			return '';
	}
}