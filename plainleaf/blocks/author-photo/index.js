/**
 * Author photo block, editor side. Plain ES5, no build step.
 * The photo itself is managed in Appearance > Theme settings.
 */
( function ( blocks, element, blockEditor, components, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var data = window.plainleafAuthor || { photo: '', settingsUrl: '' };

	blocks.registerBlockType( 'plainleaf/author-photo', {
		edit: function ( props ) {
			var size = props.attributes.size || 56;
			var blockProps = blockEditor.useBlockProps( {
				className: 'pl-author-photo',
				style: { '--pl-author-size': size + 'px' },
			} );
			return el(
				element.Fragment,
				null,
				el(
					blockEditor.InspectorControls,
					null,
					el(
						components.PanelBody,
						{ title: __( 'Author photo', 'plainleaf' ) },
						el( components.RangeControl, {
							label: __( 'Size (px)', 'plainleaf' ),
							value: size,
							min: 32,
							max: 240,
							onChange: function ( value ) {
								props.setAttributes( { size: value } );
							},
							__nextHasNoMarginBottom: true,
							__next40pxDefaultSize: true,
						} ),
						el(
							'p',
							null,
							el( 'a', { href: data.settingsUrl, target: '_blank', rel: 'noopener' }, __( 'Change the photo in Appearance > Theme settings', 'plainleaf' ) )
						)
					)
				),
				el( 'div', blockProps, el( 'img', { className: 'pl-author-photo__img', src: data.photo, alt: '', width: size, height: size } ) )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
