/**
 * Gato UI: the behavior shared by the admin screens of the Gato
 * plugins (Settings, dashboards), as gato-ui.css is their look.
 *
 * Help: an "i" button (`.gato-help-toggle`, with `aria-controls`)
 * shows or hides the help panel it names (`[data-help-panel]`).
 * Without JavaScript, the button stays hidden and the panel shown.
 *
 * Menus: a "⋯" menu closes on a click elsewhere, or on Escape.
 */
( function () {
	'use strict';

	function setExpanded( toggle, isExpanded ) {
		const panel = document.getElementById( toggle.getAttribute( 'aria-controls' ) );
		if ( ! panel ) {
			return;
		}
		toggle.setAttribute( 'aria-expanded', String( isExpanded ) );
		panel.hidden = ! isExpanded;
	}

	function initHelp( root ) {
		root.querySelectorAll( '.gato-help-toggle' ).forEach( ( toggle ) => {
			if ( toggle.dataset.helpReady ) {
				return;
			}
			toggle.dataset.helpReady = '1';
			toggle.hidden = false;
			setExpanded( toggle, toggle.getAttribute( 'aria-expanded' ) === 'true' );
		} );
	}

	// A "⋯" menu (`details.gato-entry-menu`) closes on a click elsewhere, or on Escape
	function closeMenus( except ) {
		document.querySelectorAll( 'details.gato-entry-menu[open]' ).forEach( ( menu ) => {
			if ( menu !== except ) {
				menu.open = false;
			}
		} );
	}

	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key !== 'Escape' ) {
			return;
		}
		const menu = document.querySelector( 'details.gato-entry-menu[open]' );
		if ( menu ) {
			menu.open = false;
			menu.querySelector( 'summary' ).focus();
		}
	} );

	document.addEventListener( 'click', ( event ) => {
		closeMenus( event.target.closest( 'details.gato-entry-menu' ) );
		const toggle = event.target.closest( '.gato-help-toggle' );
		if ( ! toggle ) {
			return;
		}
		event.preventDefault();
		setExpanded( toggle, toggle.getAttribute( 'aria-expanded' ) !== 'true' );
	} );

	window.gatoUI = { initHelp };

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', () => initHelp( document ) );
	} else {
		initHelp( document );
	}
}() );
