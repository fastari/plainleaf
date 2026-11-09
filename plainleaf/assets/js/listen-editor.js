/**
 * Post editor sidebar > Listen: choose or upload the audio version of a post.
 * Stores the media library ID in the post's plainleaf_audio_id field. When it is set,
 * a round play button shows next to the post's date; when it is empty, nothing shows.
 */
( function ( wp ) {
	if ( ! wp || ! wp.plugins || ! wp.data ) {
		return;
	}
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var Panel = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || ( wp.editPost && wp.editPost.PluginDocumentSettingPanel );
	var MediaUpload = wp.blockEditor && wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor && wp.blockEditor.MediaUploadCheck;
	var Button = wp.components.Button;
	if ( ! Panel || ! MediaUpload ) {
		return;
	}

	function ListenPanel() {
		var postType = wp.data.useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );
		var audioId = wp.data.useSelect( function ( select ) {
			var meta = select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
			return meta.plainleaf_audio_id || 0;
		}, [] );
		var media = wp.data.useSelect( function ( select ) {
			return audioId ? select( 'core' ).getMedia( audioId ) : null;
		}, [ audioId ] );
		var editPost = wp.data.useDispatch( 'core/editor' ).editPost;

		if ( 'post' !== postType ) {
			return null;
		}
		function setAudio( id ) {
			editPost( { meta: { plainleaf_audio_id: id } } );
		}

		var children = [];
		if ( audioId && media ) {
			children.push(
				el( 'audio', { key: 'audio', controls: true, preload: 'none', src: media.source_url, style: { width: '100%' } } ),
				el( 'p', { key: 'name', style: { margin: '8px 0', wordBreak: 'break-word' } }, media.title && media.title.rendered ? media.title.rendered : media.source_url )
			);
		} else if ( audioId ) {
			children.push( el( 'p', { key: 'loading' }, __( 'Loading the audio file…', 'plainleaf' ) ) );
		} else {
			children.push(
				el( 'p', { key: 'help', style: { marginTop: 0 } }, __( 'Add an audio version (MP3 or M4A) to show a play button next to the date. Without one, no player shows.', 'plainleaf' ) )
			);
		}
		children.push(
			el(
				MediaUploadCheck,
				{ key: 'upload' },
				el( MediaUpload, {
					allowedTypes: [ 'audio' ],
					value: audioId,
					onSelect: function ( item ) {
						setAudio( item && item.id ? item.id : 0 );
					},
					render: function ( props ) {
						return el(
							Button,
							{ variant: audioId ? 'secondary' : 'primary', onClick: props.open },
							audioId ? __( 'Replace audio', 'plainleaf' ) : __( 'Upload or choose audio', 'plainleaf' )
						);
					},
				} )
			)
		);
		if ( audioId ) {
			children.push(
				el(
					Button,
					{ key: 'remove', variant: 'link', isDestructive: true, style: { marginLeft: '12px' }, onClick: function () { setAudio( 0 ); } },
					__( 'Remove audio', 'plainleaf' )
				)
			);
		}
		return el( Panel, { name: 'pl-listen', title: __( 'Listen', 'plainleaf' ) }, children );
	}

	wp.plugins.registerPlugin( 'pl-listen', { render: ListenPanel } );
} )( window.wp );
