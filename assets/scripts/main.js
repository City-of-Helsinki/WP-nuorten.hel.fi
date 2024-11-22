/**
 * Theme JS building
 */
import '@babel/polyfill';
import Theme from './theme';
import './fonts';

// Export the theme controller for global usage.
window.Theme = Theme;

// Require main style file here for concatenation.
import '../styles/main.scss';

// Load fonts.
import '../fonts';
