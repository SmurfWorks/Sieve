import { createApp } from 'vue';
import _ from 'lodash';
import axios from 'axios';

window._ = _;
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Sieve from './components/Sieve.vue';

const app = createApp(Sieve, { schema: window.schema });
app.mount('main');
