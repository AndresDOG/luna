import '@mdi/font/css/materialdesignicons.css';
import { createVuetify} from 'vuetify';
import 'vuetify/styles';

export default createVuetify({
    icons: {
      defaultSet: 'mdi', // This is already the default value - only for display purposes
    },    
  });