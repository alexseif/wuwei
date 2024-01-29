//assets/goal.js
import functions from './functions';
var goals = document.querySelectorAll('.goal');

// Attach event listener to each item
goals.forEach(function (item) {
    item.addEventListener('dblclick', functions.openElementInNewTab);
});
