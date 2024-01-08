//assets/task.js
var tasks = document.querySelectorAll('.task');

// Attach event listener to each task
tasks.forEach(function(task) {
    task.addEventListener('dblclick', openLinkInNewTab);
});

function openLinkInNewTab(element) {
    console.log(element);
    var href = element.target.getAttribute("data-href");
    console.log(href);
    if (href) {
        window.open(href, "_blank");
    }
}