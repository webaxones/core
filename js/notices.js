import { SnackbarList } from '@wordpress/components'
import { useDispatch, useSelect } from '@wordpress/data'
import { store as noticesStore } from '@wordpress/notices'

export const Notices = () => {
    const notices = useSelect(
        ( select ) =>
            select( noticesStore )
                .getNotices()
                .filter( ( notice ) => notice.type === 'snackbar' ),
        []
    )
    const { removeNotice } = useDispatch( noticesStore )
    return (
        <SnackbarList
            className="edit-site-notices"
            notices={ notices }
            onRemove={ removeNotice }
        />
    )
}