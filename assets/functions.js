module.exports = {
    /**
     * @param {Event} element
     * @description function to open element in new tab based on data-href
     */
    openElementInNewTab: function (element) {
        let href = element.target.getAttribute("data-href");
        if (href) {
            window.open(href, "_blank");
        }
    }
}

