// Import jQuery globally so it's available to all scripts
// import 'bootstrap';
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import $ from 'jquery';
window.$ = window.jQuery = $;
import './plugins/perfect-scrollbar.min.js';
// import './plugins/choices.min.js';
import './plugins/chartjs.min.js';


// Import other libraries that should be globally available

import '../css/app.css';


// Import your custom JS files
import './dashboard.min.js';
import './customer1.js';
import './sweet.js';
import './check.js';




// Import flatpickr
import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;
