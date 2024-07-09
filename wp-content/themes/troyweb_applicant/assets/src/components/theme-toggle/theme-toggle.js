// Import the theme-toggle stylesheet
import './theme-toggle.scss'

//iife
(function() {
    const selector = '#utility-bar .inner .toggle'
    //const themes = ['default', 'high-contrast', 'coloralt', 'coloralt high-contrast']

    // Check if the element exists, if not, exit the function
    if (!document.querySelector(selector)) {
        return;
    }
    
    const themes = ['default', 'high-contrast']

    // Define a ThemeToggle class
    class ThemeToggle {
        // Constructor for the ThemeToggle class that sets up the initial state
        constructor(selector, themeStates = ['default', 'high-contrast'], labelText = 'Contrast Level') {
            // An array of theme states passed in as an argument or defaults to 'default' and 'high-contrast'
            this.themeStates = themeStates

            // The index of the current theme in the themeStates array
            this.currentStateIndex = 0

            // The previous state of the theme to remove from the body class later
            this.previousState = []

            // The text that would appear in the label
            this.labelText = labelText

            // Select the parent element using the provided CSS selector
            this.parentElement = document.querySelector(selector)

            // If the parentElement doesn't exist, throw an error
            if (!this.parentElement) {
                throw new Error(`No element found for selector "${ selector }"`)
            }

            // Create the container for the label and button, and append it to the parent element
            this.container = this.createContainer()

            // Create the label and append it to the container
            this.createLabel()

            // Create the button and append it to the container
            this.themeToggle = this.createButton()

            // Set the initial body class based on the current theme
            this.updateBodyClass()
        }

        // Function to split the theme state into separate classes
        splitIntoClasses(themeState) {
            return themeState.split(' ')
        }

        // Function to update the body's class based on the current theme
        updateBodyClass() {
            // Remove the previous theme classes from the body
            document.body.classList.remove(...this.previousState)

            // Split the current theme into its individual classes
            let currentClasses = this.splitIntoClasses(this.themeStates[this.currentStateIndex])

            // Add the current theme classes to the body
            document.body.classList.add(...currentClasses)

            // Update the data attribute of the button to the current theme
            this.themeToggle.dataset.state = this.themeStates[this.currentStateIndex] || 'default'

            // Set the previous state to current classes for future removal
            this.previousState = currentClasses

            // Calculate the left position of the button based on the current index and total number of themes
            let leftPosition = this.currentStateIndex === 0 ? '0.1rem' : '2.1rem'

            // Set the left position of the button using CSS variable
            this.themeToggle.style.setProperty('--button-left-position', leftPosition)
        }

        // Function to create a container div and append it to the parent element
        createContainer() {
            let container = document.createElement('div')
            container.className = 'theme-toggle-container'
            this.parentElement.appendChild(container)
            return container
        }

        // Function to create a label and append it to the container
        createLabel() {
            let label = document.createElement('span')
            label.innerText = this.labelText
            this.container.appendChild(label)
        }

        // Function to create a button, add an event listener to it, and append it to the container
        createButton() {
            let button = document.createElement('button')
            button.className = 'theme-toggle'

            // Add aria-label attribute for accessibility
            button.setAttribute('aria-label', 'Change contrast')

            // Add a click event listener to the button
            button.addEventListener('click', () => {
                // Increment the currentStateIndex and loop it back to 0 if it exceeds the number of theme states
                this.currentStateIndex = (this.currentStateIndex + 1) % this.themeStates.length

                // Update the aria-label based on the current theme
                let label = this.themeStates[this.currentStateIndex] === 'default' ? 'Switch to high contrast' : 'Switch to default contrast'
                button.setAttribute('aria-label', label)

                // Call the updateBodyClass method to update the body's class and button's position
                this.updateBodyClass()
            })

            // Append the button to the container
            this.container.appendChild(button)
            return button
        }
    }

    // Create a new instance of the ThemeToggle class with a specific selector and set of theme states
    new ThemeToggle(selector, themes)
})()
