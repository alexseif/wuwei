import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import $ from 'jquery';
//import jquery ui for draggable and sortable
import 'jquery-ui/ui/widgets/draggable';
import 'jquery-ui/ui/widgets/sortable';
require('bootstrap');
import 'select2/dist/css/select2.min.css'
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css'
// import flatpickr from "flatpickr";
// import 'flatpickr/dist/flatpickr.min.css';

// start the Stimulus application

import './bootstrap';
import './item';
import './goals';
import './tasks';
import './cigaretteLog';

$.fn.select2.defaults.set("theme", "bootstrap-5");

//TODO: Revise the future need for this and classify it
document.addEventListener('DOMContentLoaded', function () {
    const modeSwitch = document.querySelector('.mode-switch');
    if (modeSwitch) {
        modeSwitch.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            modeSwitch.classList.toggle('active');
        });
    }

    const listView = document.querySelector('.list-view');
    const gridView = document.querySelector('.grid-view');
    const projectsList = document.querySelector('.project-boxes');

    if (listView) {
        listView.addEventListener('click', function () {
            gridView.classList.remove('active');
            listView.classList.add('active');
            projectsList.classList.remove('jsGridView');
            projectsList.classList.add('jsListView');
        });
    }

    if (gridView) {
        gridView.addEventListener('click', function () {
            gridView.classList.add('active');
            listView.classList.remove('active');
            projectsList.classList.remove('jsListView');
            projectsList.classList.add('jsGridView');
        });
    }

    const messageBtn = document.querySelector('.messages-btn');
    if (messageBtn) {
        messageBtn.addEventListener('click', function () {
            document.querySelector('.messages-section').classList.add('show');
        });
    }

    const messageClose = document.querySelector('.messages-close');
    if (messageClose) {
        messageClose.addEventListener('click', function () {
            document.querySelector('.messages-section').classList.remove('show');
        });
    }

    // $('.datepicker').flatpickr({
    //     enableTime: true,
    //     dateFormat: "Y-m-d H:i",
    //     time_24hr: true,
    //     altInput:true,
    //     altFormat: 'd/m/Y H:i'
    //
    // });
});
$(() => {
    $('.select2').select2();
});