/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * vForm Validation
 */
require('./forms/vform');

/**
 * Lodash Library
 */
window._ = require('lodash');

/**
 * Vue ProgressBar
 */
require('./progress');

/**
 * Sweet Alert Plugin
 */
require('./swal');

/**
 * Custom Vue Instance for Handling Events
 */
window.Fire = new Vue();

/**
 * Passport Vue Components
 */
require('./passport');

/**
 * Load all the custom scripts for vue js
 * in Laravel Application
 */
require('./main');
