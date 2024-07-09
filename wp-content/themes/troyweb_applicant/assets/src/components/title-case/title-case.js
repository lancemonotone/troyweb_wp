// Test
function toTitleCase(str) {
    if (str === str.toUpperCase()) {
        return str.toLowerCase().replace(/(?:^|\s)\w/g, function(match) {
            return match.toUpperCase();
        });
    } else {
        return str;
    }
}

let elements = document.querySelectorAll('.card:not(.external-link-card) .header-sm, .card:not(.external-link-card) .card-heading');

elements.forEach(element => {
    // Process each child node separately
    element.childNodes.forEach(node => {
        if (node.nodeType === Node.TEXT_NODE) {
            // Change only the text content, preserving any HTML tags
            node.nodeValue = toTitleCase(node.nodeValue);
        }
    });
});
