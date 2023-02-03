import DetailField from './components/DetailField';
import FormField from './components/FormField';
import IndexField from './components/IndexField';

Nova.booting( ( app, store ) => {
    app.component( 'index-password-generator', IndexField );
    app.component( 'detail-password-generator', DetailField );
    app.component( 'form-password-generator', FormField );
} );
