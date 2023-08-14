//assets/item.js
var items = document.querySelectorAll('.item');

// Attach event listener to each item
items.forEach(function(item) {
    item.addEventListener('dblclick', openLinkInNewTab);
});

function openLinkInNewTab(element) {
    console.log(element);
    var href = element.target.getAttribute("data-href");
    console.log(href);
    if (href) {
        window.open(href, "_blank");
    }
}