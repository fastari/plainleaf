/**
 * Newsletter form block, editor side. Shows a preview; the real form is
 * rendered on the front end from Appearance > Theme settings > Newsletter. Plain ES5, no build step.
 */
( function ( blocks, element, blockEditor, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'plainleaf/newsletter-form', {
		edit: function () {
			var blockProps = blockEditor.useBlockProps( { className: 'pl-signup' } );
			return el(
				'div',
				blockProps,
				el(
					'form',
					{
						className: 'pl-signup__form',
						onSubmit: function ( event ) {
							event.preventDefault();
						},
					},
					el( 'label', null, __( 'Your email address', 'plainleaf' ) ),
					el( 'input', { type: 'email', placeholder: 'you@example.com', disabled: true } ),
					el( 'button', { type: 'button', disabled: true }, __( 'Subscribe', 'plainleaf' ) )
				),
				el(
					'p',
					{ className: 'pl-signup__note has-x-small-font-size' },
					__( 'Preview. The live form comes from Appearance > Theme settings.', 'plainleaf' )
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.i18n );
