/**
 * Logo block, editor side. Plain ES5 so the theme needs no build step.
 * The front end is rendered by render.php.
 */
( function ( blocks, element, blockEditor, components, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;
	var data = window.plainleafWordmark || { logo: '', icon: '', name: '', mode: 'auto', settingsUrl: '' };

	blocks.registerBlockType( 'plainleaf/wordmark', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var showLogo = !! data.logo && 'title' !== data.mode;
			var showTitle = ! showLogo || 'both' === data.mode;
			var blockProps = blockEditor.useBlockProps( {
				className: 'pl-wordmark pl-wordmark--' + attributes.size,
			} );

			return el(
				element.Fragment,
				null,
				el(
					blockEditor.InspectorControls,
					null,
					el(
						components.PanelBody,
						{ title: __( 'Logo settings', 'plainleaf' ) },
						el( components.ToggleControl, {
							label: __( 'Link to the home page', 'plainleaf' ),
							checked: attributes.isLink,
							onChange: function ( value ) {
								props.setAttributes( { isLink: value } );
							},
							__nextHasNoMarginBottom: true,
						} ),
						el( components.ToggleControl, {
							label: __( 'Use the compact logo on phones', 'plainleaf' ),
							checked: attributes.showIcon,
							onChange: function ( value ) {
								props.setAttributes( { showIcon: value } );
							},
							__nextHasNoMarginBottom: true,
						} ),
						el( components.SelectControl, {
							label: __( 'Size', 'plainleaf' ),
							value: attributes.size,
							options: [
								{ label: __( 'Header', 'plainleaf' ), value: 'header' },
								{ label: __( 'Footer', 'plainleaf' ), value: 'footer' },
							],
							onChange: function ( value ) {
								props.setAttributes( { size: value } );
							},
							__next40pxDefaultSize: true,
							__nextHasNoMarginBottom: true,
						} )
					)
				),
				el(
					'div',
					blockProps,
					showLogo && el( 'img', { className: 'pl-wordmark__text', src: data.logo, alt: '' } ),
					showTitle && el( 'span', { className: 'pl-wordmark__title' }, data.name )
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n );
