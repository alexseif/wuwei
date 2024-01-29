//assets/item.js
import functions from './functions';
var items = document.querySelectorAll('.item');

// Attach event listener to each item
items.forEach(function(item) {
    item.addEventListener('dblclick', functions.openElementInNewTab);
});

