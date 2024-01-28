//assets/task.js
import functions from './functions';
var tasks = document.querySelectorAll('.task');

// Attach event listener to each task
tasks.forEach(function(task) {
    task.addEventListener('dblclick', functions.openElementInNewTab);
});
