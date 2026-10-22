/**
 * Settings page.
 *
 * Every setting is printed by PHP, from its definition in its
 * ModuleResolver. This script knows no setting: it enhances
 * the HTML that was printed, which also works without it.
 */
( function () {
	'use strict';

	const l10n = window.gatoSettingsL10n || {};
	const format = ( text, ...values ) => {
		let index = 0;
		return String( text || '' )
			.replace( /%(\d+)\$s/g, ( match, position ) => String( values[ Number( position ) - 1 ] ) )
			.replace( /%s/g, () => String( values[ index++ ] ) );
	};
	const OWN_FIELD_NAMES = [ 'option_page', 'action', '_wpnonce', '_wp_http_referer', 'form-origin' ];

	/**
	 * Legacy: the links that show the settings hidden via CSS_STYLE
	 */
	function initShowSettingsItems( page ) {
		page.querySelectorAll( '.gatographql-settings-section a.gatographql-show-settings-items' ).forEach( ( link ) => {
			link.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				link.closest( '.gatographql-settings-section' ).querySelectorAll( '.gatographql-settings-item' ).forEach( ( item ) => {
					item.style.display = '';
				} );
			} );
		} );
	}

	/**
	 * The values of a field, as they would be submitted
	 */
	function getFieldValues( form, name ) {
		const values = [];
		Array.from( form.elements ).forEach( ( element ) => {
			if ( element.name !== name || element.disabled ) {
				return;
			}
			if ( element.type === 'checkbox' || element.type === 'radio' ) {
				if ( element.checked ) {
					values.push( element.value );
				}
				return;
			}
			if ( element.tagName === 'SELECT' ) {
				Array.from( element.selectedOptions ).forEach( ( option ) => values.push( option.value ) );
				return;
			}
			values.push( element.value );
		} );
		return values;
	}

	function setFieldValues( form, name, values ) {
		Array.from( form.elements ).forEach( ( element ) => {
			if ( element.name !== name ) {
				return;
			}
			if ( element.type === 'checkbox' || element.type === 'radio' ) {
				element.checked = values.includes( element.value );
			} else if ( element.tagName === 'SELECT' ) {
				Array.from( element.options ).forEach( ( option ) => {
					option.selected = values.includes( option.value );
				} );
			} else {
				element.value = values.length ? values[ 0 ] : '';
			}
			element.dispatchEvent( new Event( 'input', { bubbles: true } ) );
			element.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		} );
	}

	function getFieldNames( container ) {
		const names = [];
		container.querySelectorAll( 'input[name], select[name], textarea[name]' ).forEach( ( element ) => {
			if ( ! names.includes( element.name ) && ! OWN_FIELD_NAMES.includes( element.name ) ) {
				names.push( element.name );
			}
		} );
		return names;
	}

	const sameValues = ( a, b ) => a.length === b.length && a.every( ( value, index ) => value === b[ index ] );

	/**
	 * The text of a tab, without its "unsaved changes" dot
	 */
	const getLabel = ( element ) => ( element.querySelector( '.gato-sidenav__label' ) || element ).textContent.trim();

	/**
	 * Primary tabs on a single line: those that do not fit go
	 * under "More", keeping the active one always visible
	 */
	function initMoreTabs( page ) {
		const wrapper = page.querySelector( ':scope > .nav-tab-container > .gato-tabs' );
		const more = wrapper && wrapper.querySelector( '.gato-tabs__more' );
		if ( ! more ) {
			return { refresh() {} };
		}
		const tabs = Array.from( wrapper.querySelectorAll( ':scope > a.nav-tab' ) );
		const button = more.querySelector( '.gato-tabs__more-button' );
		const label = more.querySelector( '.gato-tabs__more-label' );
		const menu = more.querySelector( '.gato-tabs__menu' );

		const closeMenu = () => {
			menu.hidden = true;
			button.setAttribute( 'aria-expanded', 'false' );
		};

		const layout = () => {
			tabs.forEach( ( tab ) => tab.classList.remove( 'is-overflowing' ) );
			more.hidden = true;
			const available = wrapper.clientWidth;
			const widths = tabs.map( ( tab ) => tab.getBoundingClientRect().width + 8 );
			const total = widths.reduce( ( sum, width ) => sum + width, 0 );
			if ( total <= available ) {
				menu.replaceChildren();
				closeMenu();
				return;
			}
			more.hidden = false;
			const activeIndex = Math.max( 0, tabs.findIndex( ( tab ) => tab.classList.contains( 'nav-tab-active' ) ) );
			let budget = available - more.getBoundingClientRect().width - 16 - widths[ activeIndex ];
			let isFitting = true;
			const overflowing = [];
			tabs.forEach( ( tab, index ) => {
				if ( index === activeIndex ) {
					return;
				}
				if ( isFitting && widths[ index ] <= budget ) {
					budget -= widths[ index ];
					return;
				}
				isFitting = false;
				overflowing.push( tab );
			} );
			overflowing.forEach( ( tab ) => tab.classList.add( 'is-overflowing' ) );
			label.textContent = format( l10n.more, overflowing.length );
			menu.replaceChildren( ...overflowing.map( ( tab ) => {
				const item = document.createElement( 'button' );
				item.type = 'button';
				item.className = 'gato-tabs__menu-item';
				item.setAttribute( 'role', 'menuitem' );
				item.textContent = getLabel( tab );
				const dot = tab.querySelector( '.gato-dot' );
				if ( dot && ! dot.hidden ) {
					item.append( dot.cloneNode( true ) );
				}
				item.addEventListener( 'click', () => {
					closeMenu();
					tab.click();
				} );
				return item;
			} ) );
			const moreDot = button.querySelector( '.gato-dot' );
			moreDot.hidden = ! overflowing.some( ( tab ) => ! tab.querySelector( '.gato-dot' ).hidden );
		};

		button.addEventListener( 'click', () => {
			const isOpen = ! menu.hidden;
			menu.hidden = isOpen;
			button.setAttribute( 'aria-expanded', String( ! isOpen ) );
		} );
		document.addEventListener( 'click', ( event ) => {
			if ( ! more.contains( event.target ) ) {
				closeMenu();
			}
		} );
		document.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'Escape' ) {
				closeMenu();
			}
		} );
		tabs.forEach( ( tab ) => tab.addEventListener( 'click', () => window.setTimeout( layout, 0 ) ) );
		window.addEventListener( 'resize', layout );
		layout();
		return { refresh: layout };
	}

	/**
	 * Show (or go to) the panel of a setting: its primary tab,
	 * and its secondary tab
	 */
	function revealSetting( page, setting ) {
		const primaryPanel = setting.closest( '#gatographql-primary-settings-nav-tab-content > .tab-content' );
		if ( primaryPanel && primaryPanel.style.display === 'none' ) {
			const tab = page.querySelector( ':scope > .nav-tab-container > .nav-tab-wrapper > a.nav-tab[href="#' + CSS.escape( primaryPanel.id ) + '"]' );
			if ( tab ) {
				tab.click();
			}
		}
		const modulePanel = setting.closest( '.gatographql-settings-section' );
		if ( modulePanel && modulePanel.classList.contains( 'tab-content' ) && modulePanel.style.display === 'none' ) {
			const sideTab = primaryPanel && primaryPanel.querySelector( 'a.nav-tab[data-tab-target="#' + CSS.escape( modulePanel.id ) + '"]' );
			if ( sideTab ) {
				sideTab.click();
			}
		}
	}

	function getSettingPath( page, setting ) {
		const parts = [];
		const primaryPanel = setting.closest( '#gatographql-primary-settings-nav-tab-content > .tab-content' );
		if ( primaryPanel ) {
			const tab = page.querySelector( ':scope > .nav-tab-container > .nav-tab-wrapper > a.nav-tab[href="#' + CSS.escape( primaryPanel.id ) + '"]' );
			if ( tab ) {
				parts.push( getLabel( tab ) );
			}
		}
		const modulePanel = setting.closest( '.gatographql-settings-section' );
		if ( modulePanel && modulePanel.dataset.moduleName ) {
			parts.push( modulePanel.dataset.moduleName );
		}
		return parts.join( ' › ' );
	}

	/**
	 * Search all settings, across all tabs
	 */
	function initSearch( page ) {
		const search = page.querySelector( '.gato-search' );
		if ( ! search ) {
			return;
		}
		const input = search.querySelector( '.gato-search__input' );
		const results = search.querySelector( '.gato-search__results' );
		const settings = Array.from( page.querySelectorAll( '.gato-setting[data-search]' ) );
		let activeIndex = -1;

		const close = () => {
			results.hidden = true;
			results.replaceChildren();
			activeIndex = -1;
		};
		const goTo = ( setting ) => {
			close();
			revealSetting( page, setting );
			setting.scrollIntoView( { behavior: 'smooth', block: 'center' } );
			setting.classList.remove( 'is-found' );
			// Restart the animation
			void setting.offsetWidth;
			setting.classList.add( 'is-found' );
			const control = setting.querySelector( 'input:not([type=hidden]), select, textarea' );
			if ( control ) {
				control.focus( { preventScroll: true } );
			}
		};
		const render = () => {
			const terms = input.value.trim().toLowerCase().split( /\s+/ ).filter( Boolean );
			if ( ! terms.length || input.value.trim().length < 2 ) {
				close();
				return;
			}
			const matches = settings.filter( ( setting ) => terms.every( ( term ) => setting.dataset.search.includes( term ) ) ).slice( 0, 30 );
			activeIndex = -1;
			if ( ! matches.length ) {
				const empty = document.createElement( 'div' );
				empty.className = 'gato-search__empty';
				empty.textContent = l10n.noResults;
				results.replaceChildren( empty );
				results.hidden = false;
				return;
			}
			results.replaceChildren( ...matches.map( ( setting ) => {
				const item = document.createElement( 'button' );
				item.type = 'button';
				item.className = 'gato-search__result';
				item.setAttribute( 'role', 'option' );
				const title = document.createElement( 'span' );
				title.className = 'gato-search__result-title';
				title.textContent = setting.dataset.title || setting.dataset.search.slice( 0, 80 );
				const path = document.createElement( 'span' );
				path.className = 'gato-search__result-path';
				path.textContent = getSettingPath( page, setting );
				item.append( title, path );
				item.addEventListener( 'click', () => goTo( setting ) );
				return item;
			} ) );
			results.hidden = false;
		};
		const setActive = ( index ) => {
			const items = Array.from( results.querySelectorAll( '.gato-search__result' ) );
			if ( ! items.length ) {
				return;
			}
			activeIndex = ( index + items.length ) % items.length;
			items.forEach( ( item, itemIndex ) => item.classList.toggle( 'is-active', itemIndex === activeIndex ) );
			items[ activeIndex ].scrollIntoView( { block: 'nearest' } );
		};

		input.addEventListener( 'input', render );
		input.addEventListener( 'focus', render );
		input.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'ArrowDown' ) {
				event.preventDefault();
				setActive( activeIndex + 1 );
			} else if ( event.key === 'ArrowUp' ) {
				event.preventDefault();
				setActive( activeIndex - 1 );
			} else if ( event.key === 'Enter' ) {
				event.preventDefault();
				const items = results.querySelectorAll( '.gato-search__result' );
				const item = items[ Math.max( 0, activeIndex ) ];
				if ( item ) {
					item.click();
				}
			} else if ( event.key === 'Escape' ) {
				close();
			}
		} );
		document.addEventListener( 'click', ( event ) => {
			if ( ! search.contains( event.target ) ) {
				close();
			}
		} );
	}

	/**
	 * A setting that applies only when another one has some value
	 */
	function initDependencies( page ) {
		const dependents = Array.from( page.querySelectorAll( '.gato-setting[data-depends-on]' ) );
		if ( ! dependents.length ) {
			return;
		}
		const update = () => {
			dependents.forEach( ( setting ) => {
				const form = setting.closest( 'form' ) || page;
				const dependsOn = JSON.parse( setting.dataset.dependsOn );
				const isActive = Object.keys( dependsOn ).every( ( name ) => {
					const element = form.querySelector( '[name="' + CSS.escape( name ) + '"]' );
					if ( ! element ) {
						return true;
					}
					const values = form.tagName === 'FORM' ? getFieldValues( form, name ) : [];
					const value = values.length ? values[ 0 ] : '';
					return dependsOn[ name ].includes( value );
				} );
				setting.classList.toggle( 'is-inactive', ! isActive );
			} );
		};
		page.addEventListener( 'change', update );
		update();
	}

	/**
	 * Password fields: show or hide the value
	 */
	function initPasswordToggles( page ) {
		page.querySelectorAll( '.gato-password-toggle' ).forEach( ( button ) => {
			const input = document.getElementById( button.getAttribute( 'aria-controls' ) );
			if ( ! input ) {
				return;
			}
			button.addEventListener( 'click', () => {
				const show = input.type === 'password';
				input.type = show ? 'text' : 'password';
				button.textContent = show ? l10n.hide : l10n.show;
				button.setAttribute( 'aria-pressed', String( show ) );
			} );
		} );
	}

	/**
	 * A textarea with an entry per line, as a tag input. The
	 * textarea stays in the form, and is what is submitted.
	 */
	function initTags( page ) {
		page.querySelectorAll( 'textarea[data-tags]' ).forEach( ( textarea ) => {
			const box = document.createElement( 'div' );
			box.className = 'gato-tags';
			const input = document.createElement( 'input' );
			input.type = 'text';
			input.className = 'gato-tags__input';
			input.placeholder = textarea.placeholder || l10n.addEntry;
			input.setAttribute( 'aria-label', l10n.addEntry );
			if ( textarea.id ) {
				input.id = textarea.id + '__entry';
				const label = page.querySelector( 'label[for="' + CSS.escape( textarea.id ) + '"]' );
				if ( label ) {
					label.htmlFor = input.id;
				}
			}

			const getEntries = () => textarea.value.split( '\n' ).map( ( entry ) => entry.trim() ).filter( ( entry ) => entry !== '' );
			const setEntries = ( entries ) => {
				textarea.value = entries.join( '\n' );
				textarea.dispatchEvent( new Event( 'input', { bubbles: true } ) );
				textarea.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			};
			const render = () => {
				box.querySelectorAll( '.gato-tags__tag' ).forEach( ( tag ) => tag.remove() );
				getEntries().forEach( ( entry, index ) => {
					const tag = document.createElement( 'span' );
					tag.className = 'gato-tags__tag';
					tag.textContent = entry;
					const remove = document.createElement( 'button' );
					remove.type = 'button';
					remove.className = 'gato-tags__remove';
					remove.textContent = '×';
					remove.setAttribute( 'aria-label', format( l10n.removeEntry, entry ) );
					remove.addEventListener( 'click', () => {
						const entries = getEntries();
						entries.splice( index, 1 );
						setEntries( entries );
						input.focus();
					} );
					tag.append( remove );
					box.insertBefore( tag, input );
				} );
			};
			const addEntries = ( text ) => {
				const added = text.split( /\n/ ).map( ( entry ) => entry.trim() ).filter( ( entry ) => entry !== '' );
				if ( ! added.length ) {
					return;
				}
				setEntries( getEntries().concat( added ) );
			};

			input.addEventListener( 'keydown', ( event ) => {
				if ( event.key === 'Enter' ) {
					event.preventDefault();
					addEntries( input.value );
					input.value = '';
				} else if ( event.key === 'Backspace' && input.value === '' ) {
					const entries = getEntries();
					if ( entries.length ) {
						entries.pop();
						setEntries( entries );
					}
				}
			} );
			input.addEventListener( 'blur', () => {
				addEntries( input.value );
				input.value = '';
			} );
			input.addEventListener( 'paste', ( event ) => {
				const text = ( event.clipboardData || window.clipboardData ).getData( 'text' );
				if ( text.includes( '\n' ) ) {
					event.preventDefault();
					addEntries( text );
				}
			} );
			box.addEventListener( 'click', ( event ) => {
				if ( event.target === box ) {
					input.focus();
				}
			} );
			textarea.addEventListener( 'change', render );

			box.append( input );
			textarea.hidden = true;
			textarea.insertAdjacentElement( 'afterend', box );
			render();
		} );
	}

	/**
	 * Checklists: count, and filter when long
	 */
	function initChecklists( page ) {
		page.querySelectorAll( '.gato-checklist' ).forEach( ( checklist ) => {
			const count = checklist.querySelector( '.gato-checklist__count' );
			const filter = checklist.querySelector( '[data-checklist-filter]' );
			const items = Array.from( checklist.querySelectorAll( '.gato-checklist__item' ) );
			const update = () => {
				const checked = items.filter( ( item ) => item.querySelector( 'input' ).checked ).length;
				count.textContent = format( l10n.selectedCount, checked, items.length );
			};
			if ( filter ) {
				filter.placeholder = l10n.filter;
				filter.setAttribute( 'aria-label', l10n.filter );
				filter.addEventListener( 'input', () => {
					const term = filter.value.trim().toLowerCase();
					items.forEach( ( item ) => {
						item.hidden = term !== '' && ! item.dataset.search.includes( term );
					} );
				} );
			}
			checklist.addEventListener( 'change', update );
			update();
		} );
	}

	/**
	 * The value of an entry that "Reset" puts back
	 */
	function getDefaultValues( element ) {
		const defaultValue = element.dataset.default;
		if ( element.tagName === 'SELECT' && element.multiple ) {
			try {
				return JSON.parse( defaultValue );
			} catch ( error ) {
				return [];
			}
		}
		if ( element.type === 'checkbox' ) {
			return defaultValue === '1' ? [ element.value ] : [];
		}
		return [ defaultValue ];
	}

	function resetToDefault( container ) {
		const form = container.closest( 'form' );
		if ( ! form ) {
			return;
		}
		const done = [];
		container.querySelectorAll( '[data-default]' ).forEach( ( element ) => {
			if ( element.type === 'checkbox' ) {
				element.checked = element.dataset.default === '1';
				element.dispatchEvent( new Event( 'change', { bubbles: true } ) );
				return;
			}
			if ( done.includes( element.name ) ) {
				return;
			}
			done.push( element.name );
			setFieldValues( form, element.name, getDefaultValues( element ) );
		} );
	}

	/**
	 * Is the entry (of a grid) other than its default value?
	 */
	function isEntryChanged( row ) {
		const form = row.closest( 'form' );
		const element = row.querySelector( '[data-default]' );
		if ( ! form || ! element ) {
			return false;
		}
		return ! sameValues( getFieldValues( form, element.name ), getDefaultValues( element ) );
	}

	/**
	 * Grids: "Set all to", filter, and show only the entries
	 * other than the default
	 */
	function initGrids( page ) {
		page.querySelectorAll( '[data-grid]' ).forEach( ( grid ) => {
			const form = grid.closest( 'form' );
			const rows = Array.from( grid.querySelectorAll( '.gato-grid__row' ) );
			const filter = grid.querySelector( '[data-grid-filter]' );
			const showButtons = Array.from( grid.querySelectorAll( '[data-grid-show]' ) );
			const summary = grid.querySelector( '.gato-grid-field__summary' );
			let showOnlyChanged = false;

			const getRowName = ( row ) => {
				const element = row.querySelector( 'input[name], select[name]' );
				return element ? element.name : null;
			};
			const update = () => {
				const term = filter ? filter.value.trim().toLowerCase() : '';
				let changedCount = 0;
				const valueCounts = new Map();
				rows.forEach( ( row ) => {
					const isChanged = isEntryChanged( row );
					row.classList.toggle( 'is-changed', isChanged );
					if ( isChanged ) {
						changedCount++;
					}
					row.hidden = ( term !== '' && ! row.dataset.search.includes( term ) ) || ( showOnlyChanged && ! isChanged );
					// Only options are summarized, never typed values (eg: keys)
					const select = row.querySelector( 'select' );
					const radio = row.querySelector( 'input[type=radio]:checked' );
					let label = null;
					if ( select ) {
						label = Array.from( select.selectedOptions ).map( ( option ) => option.textContent.trim() ).join( ', ' );
					} else if ( radio ) {
						label = radio.nextElementSibling.textContent.trim();
					}
					if ( label !== null ) {
						valueCounts.set( label, ( valueCounts.get( label ) || 0 ) + 1 );
					}
				} );
				if ( showButtons.length ) {
					showButtons[ 0 ].textContent = format( l10n.showAll, rows.length );
					showButtons[ 1 ].textContent = format( l10n.showNotDefault, changedCount );
				}
				if ( summary ) {
					summary.textContent = Array.from( valueCounts.entries() ).map( ( [ label, count ] ) => count + ' ' + label ).join( ' · ' );
				}
			};

			grid.querySelectorAll( '[data-set-all]' ).forEach( ( link ) => {
				link.addEventListener( 'click', ( event ) => {
					event.preventDefault();
					if ( ! form ) {
						return;
					}
					rows.filter( ( row ) => ! row.hidden ).forEach( ( row ) => {
						const name = getRowName( row );
						if ( name ) {
							setFieldValues( form, name, [ link.dataset.setAll ] );
						}
					} );
				} );
			} );
			if ( filter ) {
				filter.placeholder = l10n.filter;
				filter.setAttribute( 'aria-label', l10n.filter );
				filter.addEventListener( 'input', update );
			}
			showButtons.forEach( ( button ) => {
				button.addEventListener( 'click', () => {
					showOnlyChanged = button.dataset.gridShow === 'changed';
					showButtons.forEach( ( other ) => other.setAttribute( 'aria-pressed', String( other === button ) ) );
					update();
				} );
			} );
			grid.addEventListener( 'change', update );
			update();
		} );
	}

	function initResetLinks( page ) {
		page.querySelectorAll( '.gato-setting__reset' ).forEach( ( link ) => {
			link.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				resetToDefault( link.closest( '.gato-setting' ) );
			} );
		} );
	}

	/**
	 * Track the unsaved changes, and save the changes of all
	 * tabs at once
	 */
	function initSaveAll( page, moreTabs ) {
		const bar = page.querySelector( '.gato-savebar' );
		const forms = Array.from( page.querySelectorAll( 'form.gato-settings-form' ) );
		if ( ! forms.length ) {
			return;
		}
		const savableForms = forms.filter( ( form ) => form.dataset.saveWithOthers === '1' );
		const canSaveAll = !! bar && !! page.dataset.saveAllUrl && savableForms.length > 0;
		const status = bar && bar.querySelector( '.gato-savebar__status' );
		const saveButton = bar && bar.querySelector( '.gato-savebar__save' );
		const discardButton = bar && bar.querySelector( '.gato-savebar__discard' );
		const submittingForms = new Set();
		let isSaving = false;

		/**
		 * The values on page load, per form and field
		 */
		const initialValues = new Map();
		const takeSnapshot = () => {
			forms.forEach( ( form ) => {
				const snapshot = new Map();
				getFieldNames( form ).forEach( ( name ) => snapshot.set( name, getFieldValues( form, name ) ) );
				initialValues.set( form, snapshot );
			} );
		};

		const getChangedSettings = ( form ) => {
			const snapshot = initialValues.get( form );
			if ( ! snapshot ) {
				return [];
			}
			return Array.from( form.querySelectorAll( '.gato-setting' ) ).filter( ( setting ) => getFieldNames( setting ).some(
				( name ) => snapshot.has( name ) && ! sameValues( snapshot.get( name ), getFieldValues( form, name ) )
			) );
		};

		const getTab = ( form ) => page.querySelector( ':scope > .nav-tab-container > .nav-tab-wrapper > a.nav-tab[href="#' + CSS.escape( form.dataset.categoryId ) + '"]' );

		const update = () => {
			const changedByForm = new Map();
			forms.forEach( ( form ) => changedByForm.set( form, getChangedSettings( form ) ) );

			forms.forEach( ( form ) => {
				const changed = changedByForm.get( form );
				const tab = getTab( form );
				if ( tab ) {
					tab.querySelector( '.gato-dot' ).hidden = changed.length === 0;
				}
				form.querySelectorAll( '.gatographql-settings-section' ).forEach( ( section ) => {
					const sideTab = page.querySelector( 'a.nav-tab[data-tab-target="#' + CSS.escape( section.id ) + '"]' );
					if ( sideTab ) {
						sideTab.querySelector( '.gato-dot' ).hidden = ! changed.some( ( setting ) => section.contains( setting ) );
					}
				} );
			} );
			moreTabs.refresh();

			if ( ! canSaveAll || isSaving ) {
				return;
			}
			const changedForms = savableForms.filter( ( form ) => changedByForm.get( form ).length > 0 );
			const total = changedForms.reduce( ( sum, form ) => sum + changedByForm.get( form ).length, 0 );
			saveButton.disabled = total === 0;
			discardButton.disabled = total === 0;
			status.classList.remove( 'is-error' );
			if ( total === 0 ) {
				status.textContent = l10n.noChanges;
				return;
			}
			status.replaceChildren();
			const strong = document.createElement( 'strong' );
			strong.textContent = total === 1 ? l10n.oneChange : format( l10n.manyChanges, total );
			status.append( strong, ' ' );
			changedForms.forEach( ( form, index ) => {
				if ( index > 0 ) {
					status.append( ' · ' );
				}
				const link = document.createElement( 'a' );
				link.href = '#' + form.dataset.categoryId;
				link.textContent = form.dataset.categoryName + ' (' + changedByForm.get( form ).length + ')';
				link.addEventListener( 'click', ( event ) => {
					event.preventDefault();
					revealSetting( page, changedByForm.get( form )[ 0 ] );
					changedByForm.get( form )[ 0 ].scrollIntoView( { behavior: 'smooth', block: 'center' } );
				} );
				status.append( link );
			} );
		};

		const isDirty = () => forms.some( ( form ) => ! submittingForms.has( form ) && getChangedSettings( form ).length > 0 );

		/**
		 * The fields of the form to submit: the option's own values
		 */
		const getEntries = ( form ) => {
			const entries = [];
			const seen = new Set();
			const optionNamePrefix = form.dataset.optionsFormName + '[';
			new FormData( form ).forEach( ( value, name ) => {
				if ( ! name.startsWith( optionNamePrefix ) || typeof value !== 'string' ) {
					return;
				}
				// A repeated name that is not a list is submitted once
				if ( ! name.endsWith( '[]' ) ) {
					if ( seen.has( name ) ) {
						return;
					}
					seen.add( name );
				}
				entries.push( [ name, value ] );
			} );
			return entries;
		};

		const getRedirectURL = () => {
			const url = new URL( window.location.href );
			url.searchParams.delete( 'settings-updated' );
			const activeTab = page.querySelector( ':scope > .nav-tab-container > .nav-tab-wrapper > a.nav-tab-active' );
			if ( activeTab ) {
				const categoryID = activeTab.getAttribute( 'href' ).replace( /^#/, '' );
				url.searchParams.set( 'category', categoryID );
				const panel = document.getElementById( categoryID );
				const activeSideTab = panel && panel.querySelector( 'a.nav-tab.nav-tab-active[data-tab-target]' );
				if ( activeSideTab ) {
					url.searchParams.set( 'module', activeSideTab.dataset.tabTarget.replace( /^#/, '' ) );
				} else {
					url.searchParams.delete( 'module' );
				}
			}
			url.hash = '';
			return url.toString();
		};

		/**
		 * The fields of the request, the meta ones first, so that
		 * PHP drops the fields past max_input_vars, never these
		 */
		const buildRequestEntries = ( batchForms, isPartial ) => {
			const prefix = 'gato-save-all';
			const meta = [ [ 'action', page.dataset.saveAllAction ] ];
			batchForms.forEach( ( form ) => {
				meta.push( [ prefix + '[tabs][]', form.dataset.optionsFormOrigin ] );
				meta.push( [ prefix + '[nonces][' + form.dataset.optionsFormOrigin + ']', form.querySelector( 'input[name="_wpnonce"]' ).value ] );
			} );
			meta.push( [ prefix + '[redirect]', getRedirectURL() ] );
			if ( isPartial ) {
				meta.push( [ prefix + '[isPartial]', '1' ] );
			}
			const fields = batchForms.flatMap( getEntries );
			const fieldCount = meta.length + 1 + fields.length;
			return [ [ prefix + '[fieldCount]', String( fieldCount ) ], ...meta, ...fields ];
		};

		/**
		 * Split the forms into requests that fit within max_input_vars
		 */
		const getBatches = ( changedForms ) => {
			const maxInputVars = Number( page.dataset.maxInputVars ) || 1000;
			const batches = [];
			let batch = [];
			let batchSize = 10;
			for ( const form of changedForms ) {
				const size = getEntries( form ).length + 2;
				if ( size + 10 > maxInputVars ) {
					throw new Error( format( l10n.tooManyFields, form.dataset.categoryName ) );
				}
				if ( batch.length && batchSize + size > maxInputVars ) {
					batches.push( batch );
					batch = [];
					batchSize = 10;
				}
				batch.push( form );
				batchSize += size;
			}
			if ( batch.length ) {
				batches.push( batch );
			}
			return batches;
		};

		const submitNatively = ( entries ) => {
			const form = document.createElement( 'form' );
			form.method = 'post';
			form.action = page.dataset.saveAllUrl;
			form.hidden = true;
			entries.forEach( ( [ name, value ] ) => {
				const input = document.createElement( 'input' );
				input.type = 'hidden';
				input.name = name;
				input.value = value;
				form.append( input );
			} );
			document.body.append( form );
			form.submit();
		};

		const saveAll = async () => {
			const changedForms = savableForms.filter( ( form ) => getChangedSettings( form ).length > 0 );
			if ( ! changedForms.length ) {
				return;
			}
			let batches;
			try {
				batches = getBatches( changedForms );
			} catch ( error ) {
				status.textContent = error.message;
				status.classList.add( 'is-error' );
				return;
			}
			isSaving = true;
			saveButton.disabled = true;
			discardButton.disabled = true;
			status.textContent = l10n.saving;
			try {
				for ( const batch of batches.slice( 0, -1 ) ) {
					const body = new URLSearchParams();
					buildRequestEntries( batch, true ).forEach( ( [ name, value ] ) => body.append( name, value ) );
					const response = await fetch( page.dataset.saveAllUrl, {
						method: 'POST',
						credentials: 'same-origin',
						body,
					} );
					if ( ! response.ok ) {
						throw new Error( response.status + ' ' + response.statusText );
					}
				}
			} catch ( error ) {
				isSaving = false;
				status.textContent = format( l10n.saveFailed, error.message );
				status.classList.add( 'is-error' );
				saveButton.disabled = false;
				discardButton.disabled = false;
				return;
			}
			changedForms.forEach( ( form ) => submittingForms.add( form ) );
			submitNatively( buildRequestEntries( batches[ batches.length - 1 ], false ) );
		};

		const discard = () => {
			forms.forEach( ( form ) => {
				const snapshot = initialValues.get( form );
				getChangedSettings( form ).forEach( ( setting ) => {
					getFieldNames( setting ).forEach( ( name ) => {
						if ( snapshot.has( name ) ) {
							setFieldValues( form, name, snapshot.get( name ) );
						}
					} );
				} );
			} );
			update();
		};

		forms.forEach( ( form ) => {
			form.addEventListener( 'submit', () => submittingForms.add( form ) );
		} );
		page.addEventListener( 'input', update );
		page.addEventListener( 'change', update );
		window.addEventListener( 'beforeunload', ( event ) => {
			if ( isSaving || ! isDirty() ) {
				return;
			}
			event.preventDefault();
			event.returnValue = '';
		} );

		if ( canSaveAll ) {
			page.classList.add( 'has-save-all' );
			saveButton.addEventListener( 'click', saveAll );
			discardButton.addEventListener( 'click', discard );
		}

		/**
		 * Other scripts may still be changing the fields as the page
		 * loads (eg: sorting the options of a select), not the user
		 */
		const start = () => {
			takeSnapshot();
			update();
		};
		if ( document.readyState === 'complete' ) {
			start();
		} else {
			window.addEventListener( 'load', start );
		}
	}

	function init() {
		const page = document.getElementById( 'gatographql-primary-settings' );
		if ( ! page ) {
			return;
		}
		page.classList.add( 'has-js' );
		page.querySelectorAll( '[data-js-only]' ).forEach( ( element ) => {
			element.hidden = false;
		} );
		initShowSettingsItems( page );
		initTags( page );
		initChecklists( page );
		initGrids( page );
		initPasswordToggles( page );
		initResetLinks( page );
		initDependencies( page );
		initSearch( page );
		const moreTabs = initMoreTabs( page );
		initSaveAll( page, moreTabs );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
