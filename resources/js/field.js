import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting( ( app, store ) => {
    app.component( 'index-password-generator', IndexField )
    app.component( 'detail-password-generator', DetailField )
    app.component( 'form-password-generator', FormField )
} )
