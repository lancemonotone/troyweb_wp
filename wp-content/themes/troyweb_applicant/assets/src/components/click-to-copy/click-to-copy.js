import './click-to-copy.scss'

document.addEventListener('DOMContentLoaded', function () {
    let wrappers = document.querySelectorAll('.click-to-copy-wrapper')
    const enableTextCopy = true // Set to false to disable text copy functionality
    const enableHtmlCopy = true // Set to false to disable HTML copy functionality
    const timeout = 2000 // Timeout in milliseconds to show the "Copied" message

    wrappers.forEach(function (wrapper) {
        // Create the content container element
        let contentContainer = document.createElement('div')
        contentContainer.className = 'click-to-copy-content'
        while (wrapper.firstChild) {
            contentContainer.appendChild(wrapper.firstChild)
        }

        // Create the click-to-copy container element
        let clickToCopyContainer = document.createElement('div')
        clickToCopyContainer.className = 'click-to-copy'
        clickToCopyContainer.appendChild(contentContainer)

        // Create a container for the buttons
        let buttonsContainer = document.createElement('div')
        buttonsContainer.className = 'click-to-copy-buttons'

        if (enableTextCopy) {
            let textButton = createCopyButton('text');
            setupCopyListener(textButton, contentContainer, 'text/plain'); // Pass the contentContainer directly
            buttonsContainer.appendChild(textButton);
        }

        if (enableHtmlCopy) {
            let htmlButton = createCopyButton('html');
            setupCopyListener(htmlButton, contentContainer, 'text/html'); // Correctly set up for HTML copying
            buttonsContainer.appendChild(htmlButton);
        }

        // Append the buttons container next to the content container
        clickToCopyContainer.appendChild(buttonsContainer)

        // Replace the wrapper with the new structure
        wrapper.parentNode.replaceChild(clickToCopyContainer, wrapper)
    })

    function createCopyButton (type) {
        let button = document.createElement('button')
        let messageLabel = type === 'text' ? 'Text' : 'HTML'
        let iconClass = type === 'text' ? 'dashicons-admin-page' : 'dashicons-editor-code'
        button.className = type === 'text' ? 'click-to-copy-button' : 'click-to-copy-html-button'
        button.setAttribute('aria-label', `Click to copy ${ type }`)
        button.innerHTML = `
            <span class="dashicons ${ iconClass }"></span>
            <span class="click-to-copy-text">Copy ${ messageLabel }</span>
        `
        return button
    }

    function setupCopyListener(button, contentContainer, type) {
        button.addEventListener('click', function () {
            if (type === 'text/plain') {
                // Fetch the latest plain text content and trim leading whitespace from each line
                let textToCopy = contentContainer.innerText.replace(/^\s+/gm, '');
                // Use the Clipboard API to copy the trimmed text content
                navigator.clipboard.writeText(textToCopy).then(function () {
                    console.log('Text successfully copied to clipboard');
                }).catch(function (err) {
                    console.error('Could not copy text: ', err);
                });
            } else if (type === 'text/html') {
                // Fetch the latest HTML content and trim leading whitespace from each line
                let htmlToCopy = contentContainer.outerHTML.replace(/^\s+/gm, '');
                let data = new Blob([htmlToCopy], { type: 'text/html' });
                navigator.clipboard.write([new ClipboardItem({ [type]: data })]).then(function () {
                    console.log('HTML successfully copied to clipboard');
                }).catch(function (err) {
                    console.error('Could not copy HTML: ', err);
                });
            }

            let textSpan = this.querySelector('.click-to-copy-text');
            let originalText = textSpan.textContent;
            textSpan.textContent = 'Copied!';
            setTimeout(function () {
                textSpan.textContent = originalText;
            }, timeout);
        });
    }


})

// Plaintext copy

// document.addEventListener('DOMContentLoaded', function () {
//     let wrappers = document.querySelectorAll('.click-to-copy-wrapper')
//
//     wrappers.forEach(function (wrapper) {
//         // Create the button element
//         let button = document.createElement('button')
//         button.className = 'click-to-copy-button'
//         button.setAttribute('aria-label', 'Click to copy')
//         button.innerHTML = `
//             <span class="dashicons dashicons-admin-page"></span>
//             <span class="click-to-copy-text">Click to copy</span>
//         `
//
//         // Create the content container element
//         let contentContainer = document.createElement('div')
//         contentContainer.className = 'click-to-copy-content'
//         // Move the inner content of the wrapper to the content container
//         while (wrapper.firstChild) {
//             contentContainer.appendChild(wrapper.firstChild)
//         }
//
//         // Create the click-to-copy container element
//         let clickToCopyContainer = document.createElement('div')
//         clickToCopyContainer.className = 'click-to-copy'
//         // Append the button and content container to the click-to-copy container
//         clickToCopyContainer.appendChild(button)
//         clickToCopyContainer.appendChild(contentContainer)
//         // Replace the wrapper with the click-to-copy container
//         wrapper.parentNode.replaceChild(clickToCopyContainer, wrapper)
//
//         // Add the event listener to the button
//         button.addEventListener('click', function () {
//             // The content to copy is within .click-to-copy-content inside the same parent
//             let contentContainer = this.nextElementSibling
//
//             if (contentContainer && contentContainer.classList.contains('click-to-copy-content')) {
//                 // Trim tabs and leading spaces from each line
//                 let contentToCopy = contentContainer.innerText.replace(/^\s+/gm, '')
//
//                 // Use the Clipboard API to copy the trimmed text content
//                 navigator.clipboard.writeText(contentToCopy).then(function () {
//                     console.log('Text successfully copied to clipboard')
//                 }).catch(function (err) {
//                     console.error('Could not copy text: ', err)
//                 })
//
//                 // Change button text to 'Copied!' and then back to 'Click to copy' after 10 seconds
//                 let textSpan = this.querySelector('.click-to-copy-text')
//                 let originalText = textSpan.textContent
//                 textSpan.textContent = 'Copied!'
//                 setTimeout(function () {
//                     textSpan.textContent = originalText
//                 }, 10000)
//             } else {
//                 console.error('Could not find the content to copy')
//             }
//         })
//     })
// })


// // HTML markup copy with progressive enhancement
// document.addEventListener('DOMContentLoaded', function() {
//     let wrappers = document.querySelectorAll('.click-to-copy-wrapper');
//
//     wrappers.forEach(function(wrapper) {
//         // Create the button element
//         let button = document.createElement('button');
//         button.className = 'click-to-copy-button';
//         button.setAttribute('aria-label', 'Click to copy');
//         button.innerHTML = `
//             <span class="dashicons dashicons-admin-page"></span>
//             <span class="click-to-copy-text">Click to copy</span>
//         `;
//
//         // Create the content container element
//         let contentContainer = document.createElement('div');
//         contentContainer.className = 'click-to-copy-content';
//         // Move the inner content of the wrapper to the content container
//         while (wrapper.firstChild) {
//             contentContainer.appendChild(wrapper.firstChild);
//         }
//
//         // Create the click-to-copy container element
//         let clickToCopyContainer = document.createElement('div');
//         clickToCopyContainer.className = 'click-to-copy';
//         // Append the button and content container to the click-to-copy container
//         clickToCopyContainer.appendChild(button);
//         clickToCopyContainer.appendChild(contentContainer);
//         // Replace the wrapper with the click-to-copy container
//         wrapper.parentNode.replaceChild(clickToCopyContainer, wrapper);
//
//         // Add the event listener to the button
//         button.addEventListener('click', function() {
//             // The content to copy is within .click-to-copy-content inside the same parent
//             let contentToCopy = contentContainer.outerHTML.replace(/^\s+/gm, '');
//
//             // Create a new blob with the HTML mime type
//             let blob = new Blob([contentToCopy], { type: 'text/html' });
//
//             // Write the blob to the clipboard
//             navigator.clipboard.write([new ClipboardItem({'text/html': blob})]).then(function() {
//                 console.log('HTML successfully copied to clipboard');
//             }).catch(function(err) {
//                 console.error('Could not copy HTML: ', err);
//             });
//
//             // Change button text to 'Copied!' and then back to 'Click to copy' after 10 seconds
//             let textSpan = this.querySelector('.click-to-copy-text');
//             let originalText = textSpan.textContent;
//             textSpan.textContent = 'Copied!';
//             setTimeout(function() {
//                 textSpan.textContent = originalText;
//             }, 10000);
//         });
//     });
// });

