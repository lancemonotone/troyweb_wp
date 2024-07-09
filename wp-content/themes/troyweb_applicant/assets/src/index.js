/**
 * Theme Styles
 *
 * See vite.config.js for global scss styles.
 * Includes the following:
 * '../src/scss/utility/_normalize.scss';
 * '../src/scss/utility/_a11y.scss';
 * '../src/scss/utility/_theme.scss';
 * '../src/scss/utility/_print.scss';
 * '../src/scss/utility/_typography.scss';
 */

// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap'

/**  Local Styles */
import './index.scss'

/** Components */
// Add class to populated inputs
import './components/select-populated-form-fields/select-populated-form-fields.js'

// Smooth scroll
import './components/smooth-scroll/smooth-scroll.js'

// Transform UPPERCASE titles to Title Case
import './components/title-case/title-case.js'

// Theme color toggle
import './components/theme-toggle/theme-toggle.js'

// Primary navigation
import './components/primary-navigation/primary-navigation.js'

// Expando
import './components/expando/expando.js'

// Accordion
import './components/accordion/accordion.js'

// Click to copy
import './components/click-to-copy/click-to-copy.js'

/**  Layouts */
