/**
 * Listen and share buttons in a post's date row.
 *
 * Play: creates one <audio> element and a slim bar at the bottom of the screen
 * (play/pause, position slider, time, speed, close). A real audio element keeps
 * playing with the phone locked; the Media Session API puts the title and
 * controls on the lock screen.
 *
 * Share: the phone's own share sheet on touch devices; elsewhere a small menu
 * with LinkedIn, X, Email and Copy link.
 */
( function () {
	'use strict';

	var root = document.querySelector( '[data-pl-actions]' );
	if ( ! root ) {
		return;
	}
	var labels = JSON.parse( root.getAttribute( 'data-labels' ) || '{}' );
	var icons = JSON.parse( root.getAttribute( 'data-icons' ) || '{}' );

	/* ---------- Share ---------- */
	var shareBtn = root.querySelector( '[data-pl-share]' );
	var menu = shareBtn ? document.getElementById( shareBtn.getAttribute( 'aria-controls' ) ) : null;

	function closeMenu( focusButton ) {
		if ( ! menu || menu.hidden ) {
			return;
		}
		menu.hidden = true;
		shareBtn.setAttribute( 'aria-expanded', 'false' );
		if ( focusButton ) {
			shareBtn.focus();
		}
	}

	if ( shareBtn && menu ) {
		var coarse = window.matchMedia && window.matchMedia( '(pointer: coarse)' ).matches;
		shareBtn.addEventListener( 'click', function () {
			if ( coarse && navigator.share ) {
				navigator.share( { title: shareBtn.getAttribute( 'data-title' ), url: shareBtn.getAttribute( 'data-url' ) } ).catch( function () {} );
				return;
			}
			var open = menu.hidden;
			menu.hidden = ! open;
			shareBtn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( open ) {
				var first = menu.querySelector( 'a, button' );
				if ( first ) {
					first.focus();
				}
			}
		} );
		document.addEventListener( 'click', function ( event ) {
			if ( ! menu.hidden && ! menu.contains( event.target ) && ! shareBtn.contains( event.target ) ) {
				closeMenu( false );
			}
		} );
		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				closeMenu( true );
			}
		} );
		menu.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( 'a' ) ) {
				closeMenu( false );
			}
		} );
		var copy = menu.querySelector( '[data-pl-copy]' );
		if ( copy ) {
			copy.addEventListener( 'click', function () {
				var url = shareBtn.getAttribute( 'data-url' );
				var label = copy.querySelector( 'span' );
				var original = label.textContent;
				var done = function () {
					label.textContent = labels.copied || original;
					setTimeout( function () {
						label.textContent = original;
						closeMenu( true );
					}, 1400 );
				};
				if ( navigator.clipboard && navigator.clipboard.writeText ) {
					navigator.clipboard.writeText( url ).then( done, function () {
						window.prompt( original, url ); // eslint-disable-line no-alert
					} );
				} else {
					window.prompt( original, url ); // eslint-disable-line no-alert
				}
			} );
		}
	}

	/* ---------- Listen ---------- */
	var listenBtn = root.querySelector( '[data-pl-listen]' );
	if ( ! listenBtn ) {
		return;
	}
	var audio = null;
	var bar = null;
	var speeds = [ 1, 1.25, 1.5, 1.75, 2, 0.75 ];
	var speedIndex = 0;
	var parts = {};

	function time( seconds ) {
		if ( ! isFinite( seconds ) || seconds < 0 ) {
			seconds = 0;
		}
		var m = Math.floor( seconds / 60 );
		var s = Math.floor( seconds % 60 );
		return m + ':' + ( s < 10 ? '0' : '' ) + s;
	}

	function setState() {
		var playing = audio && ! audio.paused;
		[ listenBtn, parts.toggle ].forEach( function ( button ) {
			if ( ! button ) {
				return;
			}
			button.innerHTML = playing ? icons.pause : icons.play;
			button.setAttribute( 'aria-label', playing ? labels.pause : labels.listen );
			button.classList.toggle( 'is-playing', playing );
		} );
		if ( 'mediaSession' in navigator ) {
			navigator.mediaSession.playbackState = playing ? 'playing' : 'paused';
		}
	}

	function update() {
		if ( ! audio || ! parts.range ) {
			return;
		}
		var duration = isFinite( audio.duration ) ? audio.duration : 0;
		parts.range.max = duration ? Math.floor( duration ) : 0;
		parts.range.value = Math.floor( audio.currentTime );
		parts.range.setAttribute( 'aria-valuetext', time( audio.currentTime ) + ' / ' + time( duration ) );
		parts.range.style.setProperty( '--pl-progress', duration ? ( audio.currentTime / duration ) * 100 + '%' : '0%' );
		parts.time.textContent = time( audio.currentTime ) + ' / ' + time( duration );
	}

	function buildBar() {
		bar = document.createElement( 'div' );
		bar.className = 'pl-player';
		bar.setAttribute( 'role', 'region' );
		bar.setAttribute( 'aria-label', labels.player );
		bar.innerHTML =
			'<div class="pl-player__inner">' +
			'<button type="button" class="pl-action pl-player__toggle"></button>' +
			'<input type="range" class="pl-player__range" min="0" max="0" step="1" value="0">' +
			'<span class="pl-player__time" aria-hidden="true">0:00 / 0:00</span>' +
			'<button type="button" class="pl-player__speed">1×</button>' +
			'<button type="button" class="pl-player__close"></button>' +
			'</div>';
		document.body.appendChild( bar );
		parts.toggle = bar.querySelector( '.pl-player__toggle' );
		parts.range = bar.querySelector( '.pl-player__range' );
		parts.time = bar.querySelector( '.pl-player__time' );
		parts.speed = bar.querySelector( '.pl-player__speed' );
		parts.close = bar.querySelector( '.pl-player__close' );
		parts.range.setAttribute( 'aria-label', labels.seek );
		parts.speed.setAttribute( 'aria-label', labels.speed + ': 1×' );
		parts.close.setAttribute( 'aria-label', labels.close );
		parts.close.innerHTML = icons.close;

		parts.toggle.addEventListener( 'click', toggle );
		parts.range.addEventListener( 'input', function () {
			audio.currentTime = Number( parts.range.value );
			update();
		} );
		parts.speed.addEventListener( 'click', function () {
			speedIndex = ( speedIndex + 1 ) % speeds.length;
			audio.playbackRate = speeds[ speedIndex ];
			parts.speed.textContent = speeds[ speedIndex ] + '×';
			parts.speed.setAttribute( 'aria-label', labels.speed + ': ' + speeds[ speedIndex ] + '×' );
		} );
		parts.close.addEventListener( 'click', function () {
			audio.pause();
			bar.classList.remove( 'is-open' );
			document.body.classList.remove( 'pl-has-player' );
			listenBtn.focus();
		} );
		requestAnimationFrame( function () {
			bar.classList.add( 'is-open' );
		} );
	}

	function setupAudio() {
		audio = new Audio();
		audio.preload = 'metadata';
		audio.src = listenBtn.getAttribute( 'data-src' );
		[ 'play', 'pause', 'ended' ].forEach( function ( name ) {
			audio.addEventListener( name, setState );
		} );
		[ 'timeupdate', 'loadedmetadata', 'durationchange' ].forEach( function ( name ) {
			audio.addEventListener( name, update );
		} );
		if ( 'mediaSession' in navigator && window.MediaMetadata ) {
			var artwork = listenBtn.getAttribute( 'data-artwork' );
			navigator.mediaSession.metadata = new window.MediaMetadata( {
				title: listenBtn.getAttribute( 'data-title' ),
				artist: listenBtn.getAttribute( 'data-artist' ),
				artwork: artwork ? [ { src: artwork, sizes: '512x512', type: 'image/png' } ] : [],
			} );
			var set = function ( action, handler ) {
				try {
					navigator.mediaSession.setActionHandler( action, handler );
				} catch ( e ) {}
			};
			set( 'play', function () { audio.play(); } );
			set( 'pause', function () { audio.pause(); } );
			set( 'seekbackward', function () { audio.currentTime = Math.max( 0, audio.currentTime - 15 ); } );
			set( 'seekforward', function () { audio.currentTime = Math.min( audio.duration || 0, audio.currentTime + 15 ); } );
			set( 'seekto', function ( details ) { audio.currentTime = details.seekTime; } );
		}
	}

	function toggle() {
		if ( ! audio ) {
			setupAudio();
		}
		if ( ! bar ) {
			buildBar();
		}
		bar.classList.add( 'is-open' );
		document.body.classList.add( 'pl-has-player' );
		if ( audio.paused ) {
			audio.play().catch( function () {
				setState();
			} );
		} else {
			audio.pause();
		}
	}

	listenBtn.addEventListener( 'click', toggle );
} )();
