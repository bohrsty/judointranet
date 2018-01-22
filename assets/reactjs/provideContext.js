/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

"use strict";

// import required modules
import PropTypes from 'prop-types';
import createProvider from 'react-provide-props';

/**
 * function to create a provider adding the required props
 */
export default createProvider('ContextProvider', (props, context) => ({
	addNotification: context.addNotification,
	startLoading: context.startLoading,
	stopLoading: context.stopLoading
}), {
}, {
	addNotification: PropTypes.func.isRequired,
	startLoading: PropTypes.func.isRequired,
	stopLoading: PropTypes.func.isRequired
});
