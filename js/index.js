import api from '@wordpress/api'
import { Button, Icon, TabPanel, Panel, PanelBody, PanelRow, Placeholder, SelectControl, Spinner, TextControl, ToggleControl, SnackbarList } from '@wordpress/components'
import { dispatch, useDispatch, useSelect } from '@wordpress/data'
import { render, Component } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { store as noticesStore } from '@wordpress/notices'
import { String } from './string.js'

const Notices = () => {
    const notices = useSelect(
        ( select ) =>
            select( noticesStore )
                .getNotices()
                .filter( ( notice ) => notice.type === 'snackbar' ),
        []
    )
    const { removeNotice } = useDispatch( noticesStore );
    return (
        <SnackbarList
            className="edit-site-notices"
            notices={ notices }
            onRemove={ removeNotice }
        />
    )
}

const onSelect = ( tabName ) => {
    console.log( 'Selecting tab', tabName );
};

class App extends Component {
    constructor() {
        super( ...arguments )

		this.state = {}

		settingsGroup.fields.forEach( field => {
			this.state[field.slug] = ''
		} )

		this.state['isAPILoaded'] = false
    }

	componentDidMount() {

        api.loadPromise.then( () => {
            this.settings = new api.models.Settings()

            const { isAPILoaded } = this.state

            if ( isAPILoaded === false ) {
                this.settings.fetch().then( ( response ) => {
					settingsGroup.fields.forEach( field => {
						this.setState( {
							[field.slug]: response[ field.slug ],
						} )
					} )
					this.setState( {
						['isAPILoaded']: true
					} )
                } )
            }
        } )
    }

	handleOnChange = (fieldSlug, value) => {
		this.setState( { [fieldSlug]: value } )
	}

    render() {
		if ( ! this.state.isAPILoaded ) {
            return (
                <Placeholder>
                    <Spinner />
                </Placeholder>
            )
        } 

        return (
            <>
				{ settingsGroup.fields.map( field => {
					return <String key={ field.slug } onChange={ this.handleOnChange } state={ this.state } field={ field } />
				} ) }

				<Button
					className = 'button button-primary'
					onClick={ () => {
						const values = this.state
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
