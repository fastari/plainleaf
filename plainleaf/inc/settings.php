<?php
/**
 * Appearance > Theme settings.
 *
 * One page for the few things the theme needs that are not design:
 *   - Author photo (shown next to the byline in the home hero, About and posts).
 *   - Header: logo, title or both, and an optional compact logo for phones.
 *   - Newsletter connection (plugin shortcode or form action URL) and button text.
 *   - Social links, the footer credit, and whether the site offers RSS feeds.
 *
 * Colors, fonts and the logo itself are not here: they live in the Site Editor
 * (Appearance > Editor > Styles, and the Site Logo block), so theme updates
 * never touch them.
 *
 * Every setting is also exposed over the REST API (/wp/v2/settings) so it can be
 * set by automation, for example an AI assistant through MCP.
 *
 * @package Plainleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the settings.
 */
function plainleaf_register_settings() {
	$settings = array(
		'plainleaf_author_photo'           => array(
			'type'              => 'integer',
			'description'       => __( 'Media library ID of the author photo.', 'plainleaf' ),
			'sanitize_callback' => 'absint',
			'default'           => 0,
		),
		'plainleaf_logo'             => array(
			'type'              => 'integer',
			'description'       => __( 'Media library ID of the logo shown in the header and footer.', 'plainleaf' ),
			'sanitize_callback' => 'absint',
			'default'           => 0,
		),
		'plainleaf_logo_phone'       => array(
			'type'              => 'integer',
			'description'       => __( 'Media library ID of a compact logo shown in the header on phones.', 'plainleaf' ),
			'sanitize_callback' => 'absint',
			'default'           => 0,
		),
		'plainleaf_logo_mode'        => array(
			'type'              => 'string',
			'description'       => __( 'What the header shows: auto (logo if set, otherwise the site title), both, or title.', 'plainleaf' ),
			'sanitize_callback' => 'plainleaf_sanitize_logo_mode',
			'default'           => 'auto',
		),
		'plainleaf_show_credit'      => array(
			'type'              => 'boolean',
			'description'       => __( 'Show the "Theme by Fastari" link in the footer.', 'plainleaf' ),
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => true,
		),
		'plainleaf_hero_media'       => array(
			'type'              => 'integer',
			'description'       => __( 'Media library ID of an image or video shown in the home page hero band.', 'plainleaf' ),
			'sanitize_callback' => 'absint',
			'default'           => 0,
		),
		'plainleaf_hero_layout'      => array(
			'type'              => 'string',
			'description'       => __( 'Where the hero image or video goes: right (right half) or full (whole band, tinted so text stays readable).', 'plainleaf' ),
			'sanitize_callback' => 'plainleaf_sanitize_hero_layout',
			'default'           => 'right',
		),
		'plainleaf_disable_feeds'    => array(
			'type'              => 'boolean',
			'description'       => __( 'Turn off RSS and Atom feeds and send feed addresses to the blog page.', 'plainleaf' ),
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => false,
		),
		'plainleaf_newsletter_shortcode'   => array(
			'type'              => 'string',
			'description'       => __( 'Shortcode from a newsletter plugin that renders the signup form.', 'plainleaf' ),
			'sanitize_callback' => 'plainleaf_newsletter_sanitize_shortcode',
			'default'           => '',
		),
		'plainleaf_newsletter_action'      => array(
			'type'              => 'string',
			'description'       => __( 'Form action URL from a hosted newsletter tool.', 'plainleaf' ),
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		),
		'plainleaf_newsletter_email_field' => array(
			'type'              => 'string',
			'description'       => __( 'Name of the email field the newsletter tool expects.', 'plainleaf' ),
			'sanitize_callback' => 'plainleaf_newsletter_sanitize_field_name',
			'default'           => 'email',
		),
		'plainleaf_newsletter_source_field' => array(
			'type'              => 'string',
			'description'       => __( 'Name of a hidden field that tells the newsletter tool where on the site someone signed up.', 'plainleaf' ),
			'sanitize_callback' => 'plainleaf_newsletter_sanitize_optional_field_name',
			'default'           => '',
		),
		'plainleaf_newsletter_redirect'    => array(
			'type'              => 'string',
			'description'       => __( 'Page to send people to after they sign up.', 'plainleaf' ),
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		),
		'plainleaf_newsletter_button'      => array(
			'type'              => 'string',
			'description'       => __( 'Text on the newsletter signup button.', 'plainleaf' ),
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		),
	);
	foreach ( plainleaf_social_services() as $service => $label ) {
		$settings[ 'plainleaf_social_' . $service ] = array(
			'type'              => 'string',
			/* translators: %s: social network name, such as LinkedIn. */
			'description'       => sprintf( __( 'Link to your %s profile, shown as an icon in the footer.', 'plainleaf' ), $label ),
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		);
	}
	foreach ( $settings as $name => $args ) {
		$args['show_in_rest'] = true;
		register_setting( 'plainleaf_settings', $name, $args );
	}
}
add_action( 'init', 'plainleaf_register_settings' );

/**
 * Keep the hero layout to one of the known values.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function plainleaf_sanitize_hero_layout( $value ) {
	return ( is_string( $value ) && in_array( $value, array( 'right', 'full' ), true ) ) ? $value : 'right';
}

/**
 * The home hero image or video: attachment ID, kind (image|video) and layout,
 * or null when none is set.
 *
 * @return array{id:int,kind:string,layout:string}|null
 */
function plainleaf_hero_media() {
	$id = (int) get_option( 'plainleaf_hero_media', 0 );
	if ( ! $id || 'attachment' !== get_post_type( $id ) ) {
		return null;
	}
	$mime = (string) get_post_mime_type( $id );
	$kind = 0 === strpos( $mime, 'image/' ) ? 'image' : ( 0 === strpos( $mime, 'video/' ) ? 'video' : '' );
	if ( '' === $kind ) {
		return null;
	}
	return array(
		'id'     => $id,
		'kind'   => $kind,
		'layout' => plainleaf_sanitize_hero_layout( get_option( 'plainleaf_hero_layout', 'right' ) ),
	);
}

/**
 * Keep the logo mode to one of the known values.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function plainleaf_sanitize_logo_mode( $value ) {
	$value = is_string( $value ) ? $value : '';
	return in_array( $value, array( 'auto', 'both', 'title' ), true ) ? $value : 'auto';
}

/**
 * The logo's media library ID: the Theme settings logo, else the core Site Logo,
 * else the classic custom logo. 0 when none is set.
 *
 * @return int
 */
function plainleaf_logo_id() {
	foreach ( array( (int) get_option( 'plainleaf_logo', 0 ), (int) get_option( 'site_logo', 0 ), (int) get_theme_mod( 'custom_logo', 0 ) ) as $id ) {
		if ( $id && wp_attachment_is_image( $id ) ) {
			return $id;
		}
	}
	return 0;
}

/**
 * Whether the footer credit is on. Off when unticked in Theme settings, or
 * with add_filter( 'plainleaf_show_credit', '__return_false' ).
 *
 * @return bool
 */
function plainleaf_show_credit() {
	return (bool) apply_filters( 'plainleaf_show_credit', (bool) get_option( 'plainleaf_show_credit', true ) );
}

/**
 * Social networks offered on the settings page, in the order their icons appear.
 * Keys are WordPress social icon names (core/social-link "service").
 *
 * @return array<string,string>
 */
function plainleaf_social_services() {
	return array(
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'instagram' => 'Instagram',
		'x'         => 'X',
		'threads'   => 'Threads',
		'bluesky'   => 'Bluesky',
		'tiktok'    => 'TikTok',
		'github'    => 'GitHub',
	);
}

/**
 * The social links that have a URL, in display order.
 *
 * @return array<string,array{label:string,url:string}>
 */
function plainleaf_social_links() {
	$links = array();
	foreach ( plainleaf_social_services() as $service => $label ) {
		$url = trim( (string) get_option( 'plainleaf_social_' . $service, '' ) );
		if ( '' !== $url ) {
			$links[ $service ] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}
	return $links;
}

/**
 * Add the page under Appearance.
 */
function plainleaf_settings_menu() {
	$hook = add_theme_page(
		__( 'Theme settings', 'plainleaf' ),
		__( 'Theme settings', 'plainleaf' ),
		'manage_options',
		'pl-settings',
		'plainleaf_settings_page'
	);
	add_action( 'load-' . $hook, 'plainleaf_settings_assets' );
}
add_action( 'admin_menu', 'plainleaf_settings_menu' );

/**
 * Media library picker for the photo field.
 */
function plainleaf_settings_assets() {
	add_action(
		'admin_enqueue_scripts',
		function () {
			wp_enqueue_media();
			wp_add_inline_script(
				'media-editor',
				"(function(){document.addEventListener('click',function(e){
					var pick=e.target.closest('[data-pl-pick]'),clear=e.target.closest('[data-pl-clear]'),btn=pick||clear;
					if(!btn){return;}
					e.preventDefault();
					var input=document.getElementById(btn.dataset.target),preview=document.getElementById(btn.dataset.target+'-preview');
					if(!input||!preview){return;}
					if(clear){input.value='0';preview.textContent=clear.dataset.note;return;}
					var frame=wp.media({title:pick.dataset.title,button:{text:pick.dataset.button},library:{type:pick.dataset.types?pick.dataset.types.split(','):'image'},multiple:false});
					frame.on('select',function(){var a=frame.state().get('selection').first().toJSON();input.value=a.id;
						preview.textContent='';
						if(a.type!=='image'){preview.textContent=a.filename||a.title||'';return;}
						var url=(a.sizes&&(a.sizes.thumbnail||a.sizes.medium||a.sizes.full)||a).url,img=document.createElement('img');
						img.src=url;img.alt='';img.style.maxWidth='160px';img.style.maxHeight='96px';preview.appendChild(img);});
					frame.open();
				});})();"
			);
		}
	);
}

/**
 * Fields.
 */
function plainleaf_settings_fields() {
	$image_field = function ( $args ) {
		$id = (int) get_option( $args['label_for'], 0 );
		printf( '<div id="%s-preview" style="margin-bottom:8px">', esc_attr( $args['label_for'] ) );
		if ( $id && wp_attachment_is_image( $id ) ) {
			echo wp_get_attachment_image( $id, 'thumbnail', false, array( 'style' => 'max-width:160px;max-height:96px;width:auto;height:auto' ) );
		} elseif ( $id && 'attachment' === get_post_type( $id ) ) {
			echo esc_html( wp_basename( (string) get_attached_file( $id ) ) );
		} else {
			echo esc_html( $args['empty'] );
		}
		echo '</div>';
		printf(
			'<input type="hidden" id="%1$s" name="%1$s" value="%2$d">' .
			'<button type="button" class="button" data-pl-pick data-target="%1$s" data-types="%9$s" data-title="%3$s" data-button="%4$s">%5$s</button> ' .
			'<button type="button" class="button-link" data-pl-clear data-target="%1$s" data-note="%6$s">%7$s</button>' .
			'<p class="description">%8$s</p>',
			esc_attr( $args['label_for'] ),
			(int) $id,
			esc_attr( $args['title'] ),
			esc_attr__( 'Use this image', 'plainleaf' ),
			esc_html__( 'Choose or upload', 'plainleaf' ),
			esc_attr( $args['empty'] ),
			esc_html__( 'Remove', 'plainleaf' ),
			esc_html( $args['help'] ),
			esc_attr( isset( $args['types'] ) ? $args['types'] : 'image' )
		);
	};

	// Author.
	add_settings_section(
		'plainleaf_author',
		__( 'Author photo', 'plainleaf' ),
		function () {
			echo '<p>' . esc_html__( 'Shown as a small round photo next to the byline on the home page and at the end of posts. Use a square photo of at least 320 by 320 pixels. With no photo, the byline shows without one.', 'plainleaf' ) . '</p>';
		},
		'pl-settings'
	);
	add_settings_field(
		'plainleaf_author_photo',
		__( 'Photo', 'plainleaf' ),
		$image_field,
		'pl-settings',
		'plainleaf_author',
		array(
			'label_for' => 'plainleaf_author_photo',
			'title'     => __( 'Choose the author photo', 'plainleaf' ),
			'empty'     => __( 'No photo.', 'plainleaf' ),
			'help'      => '',
		)
	);

	// Header and footer.
	add_settings_section(
		'plainleaf_header',
		__( 'Header and footer', 'plainleaf' ),
		function () {
			printf(
				'<p>%s</p>',
				wp_kses(
					sprintf(
						/* translators: %s: link to Settings > General. */
						__( 'Your logo shows in the header and footer. The small browser and phone icon is the Site Icon under <a href="%s">Settings > General</a>. Colors and fonts are under Appearance > Editor > Styles.', 'plainleaf' ),
						esc_url( admin_url( 'options-general.php' ) )
					),
					array( 'a' => array( 'href' => array() ) )
				)
			);
		},
		'pl-settings'
	);
	add_settings_field(
		'plainleaf_logo',
		__( 'Logo', 'plainleaf' ),
		$image_field,
		'pl-settings',
		'plainleaf_header',
		array(
			'label_for' => 'plainleaf_logo',
			'title'     => __( 'Choose the logo', 'plainleaf' ),
			'empty'     => __( 'No logo: the header shows the site title.', 'plainleaf' ),
			'help'      => __( 'PNG, JPG or WebP, about 2x the size it shows (for example 480 by 120 pixels). WordPress does not accept SVG uploads unless a plugin allows them.', 'plainleaf' ),
		)
	);
	add_settings_field(
		'plainleaf_logo_mode',
		__( 'Header shows', 'plainleaf' ),
		function () {
			$value   = get_option( 'plainleaf_logo_mode', 'auto' );
			$options = array(
				'auto'  => __( 'The logo, or the site title until a logo is set', 'plainleaf' ),
				'both'  => __( 'The logo and the site title', 'plainleaf' ),
				'title' => __( 'Only the site title', 'plainleaf' ),
			);
			echo '<select id="plainleaf_logo_mode" name="plainleaf_logo_mode">';
			foreach ( $options as $key => $label ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $value, $key, false ), esc_html( $label ) );
			}
			echo '</select>';
		},
		'pl-settings',
		'plainleaf_header',
		array( 'label_for' => 'plainleaf_logo_mode' )
	);
	add_settings_field(
		'plainleaf_logo_phone',
		__( 'Compact logo for phones', 'plainleaf' ),
		$image_field,
		'pl-settings',
		'plainleaf_header',
		array(
			'label_for' => 'plainleaf_logo_phone',
			'title'     => __( 'Choose the compact logo', 'plainleaf' ),
			'empty'     => __( 'None: phones show the regular logo, smaller.', 'plainleaf' ),
			'help'      => __( 'Optional. A small square mark, shown instead of the logo on screens narrower than 600 pixels.', 'plainleaf' ),
		)
	);
	$checkbox_field = function ( $args ) {
		printf(
			'<input type="hidden" name="%1$s" value="0"><label><input type="checkbox" id="%1$s" name="%1$s" value="1"%2$s> %3$s</label>',
			esc_attr( $args['label_for'] ),
			checked( (bool) get_option( $args['label_for'], $args['default'] ), true, false ),
			esc_html( $args['text'] )
		);
	};
	add_settings_field(
		'plainleaf_show_credit',
		__( 'Theme credit', 'plainleaf' ),
		$checkbox_field,
		'pl-settings',
		'plainleaf_header',
		array(
			'label_for' => 'plainleaf_show_credit',
			'default'   => true,
			'text'      => __( 'Show "Theme by Fastari" in the footer. Thank you for keeping it, and feel free to turn it off.', 'plainleaf' ),
		)
	);
	add_settings_field(
		'plainleaf_disable_feeds',
		__( 'RSS feeds', 'plainleaf' ),
		$checkbox_field,
		'pl-settings',
		'plainleaf_header',
		array(
			'label_for' => 'plainleaf_disable_feeds',
			'default'   => false,
			'text'      => __( 'Turn off RSS feeds. Feed addresses then go to the blog page.', 'plainleaf' ),
		)
	);

	// Home hero.
	add_settings_section(
		'plainleaf_hero',
		__( 'Home hero', 'plainleaf' ),
		function () {
			printf(
				'<p>%s</p>',
				wp_kses(
					sprintf(
						/* translators: %s: link to the Site Editor styles. */
						__( 'Add an image or a short looping video to the colored band at the top of the home page. For your own illustration or animation in code, use Additional CSS in <a href="%s">Appearance > Editor > Styles</a> and style the hero art layer (.pl-hero-art__layer).', 'plainleaf' ),
						esc_url( admin_url( 'site-editor.php?p=%2Fstyles' ) )
					),
					array( 'a' => array( 'href' => array() ) )
				)
			);
		},
		'pl-settings'
	);
	add_settings_field(
		'plainleaf_hero_media',
		__( 'Image or video', 'plainleaf' ),
		$image_field,
		'pl-settings',
		'plainleaf_hero',
		array(
			'label_for' => 'plainleaf_hero_media',
			'types'     => 'image,video',
			'title'     => __( 'Choose the hero image or video', 'plainleaf' ),
			'empty'     => __( 'None.', 'plainleaf' ),
			'help'      => __( 'Image: JPG, PNG or WebP, at least 1600 pixels wide. Video: MP4 or WebM, muted, under about 10 MB; it loops with a pause button, and stays still for visitors who turn off motion.', 'plainleaf' ),
		)
	);
	add_settings_field(
		'plainleaf_hero_layout',
		__( 'Placement', 'plainleaf' ),
		function () {
			$value   = get_option( 'plainleaf_hero_layout', 'right' );
			$options = array(
				'right' => __( 'Right half, fading into the band', 'plainleaf' ),
				'full'  => __( 'Whole band, tinted with the brand color so the text stays readable', 'plainleaf' ),
			);
			echo '<select id="plainleaf_hero_layout" name="plainleaf_hero_layout">';
			foreach ( $options as $key => $label ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $value, $key, false ), esc_html( $label ) );
			}
			echo '</select>';
		},
		'pl-settings',
		'plainleaf_hero',
		array( 'label_for' => 'plainleaf_hero_layout' )
	);

	// Newsletter.
	add_settings_section(
		'plainleaf_newsletter',
		__( 'Newsletter', 'plainleaf' ),
		function () {
			echo '<p>' . esc_html__( 'Connect the signup form used across the site. Fill in either a plugin shortcode or a form action URL. The shortcode wins if both are set.', 'plainleaf' ) . '</p>';
		},
		'pl-settings'
	);
	$text_field = function ( $args ) {
		printf(
			'<input type="%1$s" class="regular-text code" id="%2$s" name="%2$s" value="%3$s" placeholder="%4$s"><p class="description">%5$s</p>',
			esc_attr( $args['type'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'], $args['default'] ) ),
			esc_attr( $args['placeholder'] ),
			esc_html( $args['help'] )
		);
	};
	$fields = array(
		'plainleaf_newsletter_button'      => array( __( 'Button text', 'plainleaf' ), 'text', '', __( 'Subscribe', 'plainleaf' ), __( 'Leave empty to use "Subscribe".', 'plainleaf' ) ),
		'plainleaf_newsletter_shortcode'   => array( __( 'Plugin shortcode', 'plainleaf' ), 'text', '', '[mailpoet_form id="1"]', __( 'For newsletter plugins such as MailPoet, The Newsletter Plugin or Jetpack.', 'plainleaf' ) ),
		'plainleaf_newsletter_action'      => array( __( 'Form action URL', 'plainleaf' ), 'url', '', 'https://', __( 'For hosted tools such as Kit, Buttondown or Mailchimp. Copy the action="..." address from the tool\'s HTML embed code.', 'plainleaf' ) ),
		'plainleaf_newsletter_email_field' => array( __( 'Email field name', 'plainleaf' ), 'text', 'email', 'email', __( 'Only used with a form action URL. Buttondown uses email, Kit uses email_address, Mailchimp uses EMAIL.', 'plainleaf' ) ),
		'plainleaf_newsletter_redirect'    => array( __( 'After signup, go to', 'plainleaf' ), 'url', '', 'https://', __( 'Optional. Leave empty to use your "Check your inbox" page (slug check-your-inbox) when it is published. Works with a form action URL, not with plugin shortcodes.', 'plainleaf' ) ),
		'plainleaf_newsletter_source_field' => array( __( 'Signup source field name', 'plainleaf' ), 'text', '', 'fields[signup_source]', __( 'Optional. Sends where someone signed up (home-hero, post-end, newsletter-page and so on) to your newsletter tool. For Kit, create a custom field called "Signup source" and enter fields[signup_source]. Leave empty to send nothing extra.', 'plainleaf' ) ),
	);
	foreach ( $fields as $name => $f ) {
		add_settings_field(
			$name,
			$f[0],
			$text_field,
			'pl-settings',
			'plainleaf_newsletter',
			array(
				'label_for'   => $name,
				'type'        => $f[1],
				'default'     => $f[2],
				'placeholder' => $f[3],
				'help'        => $f[4],
			)
		);
	}

	// Social links.
	add_settings_section(
		'plainleaf_social',
		__( 'Social links', 'plainleaf' ),
		function () {
			echo '<p>' . esc_html__( 'Paste the full address of each profile you want in the footer. Each one shows as its network\'s icon. Leave a field empty to hide that icon.', 'plainleaf' ) . '</p>';
		},
		'pl-settings'
	);
	foreach ( plainleaf_social_services() as $service => $label ) {
		add_settings_field(
			'plainleaf_social_' . $service,
			$label,
			$text_field,
			'pl-settings',
			'plainleaf_social',
			array(
				'label_for'   => 'plainleaf_social_' . $service,
				'type'        => 'url',
				'default'     => '',
				'placeholder' => 'https://',
				'help'        => '',
			)
		);
	}
}
add_action( 'admin_init', 'plainleaf_settings_fields' );

/**
 * Page markup.
 */
function plainleaf_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'Theme settings', 'plainleaf' ) . '</h1>';
	settings_errors();
	echo '<form action="options.php" method="post">';
	settings_fields( 'plainleaf_settings' );
	do_settings_sections( 'pl-settings' );
	submit_button();
	echo '</form></div>';
}

/**
 * Author photo markup: the chosen media library image, or an empty string when none is set.
 *
 * @param int $size Display size in CSS pixels.
 * @return string
 */
function plainleaf_author_photo_html( $size = 56 ) {
	$size = max( 24, (int) $size );
	$alt  = plainleaf_text( 'author_name' );
	$id   = (int) get_option( 'plainleaf_author_photo', 0 );
	if ( $id && wp_attachment_is_image( $id ) ) {
		$image_alt = trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
		return wp_get_attachment_image(
			$id,
			array( $size * 3, $size * 3 ),
			false,
			array(
				'class'    => 'pl-author-photo__img',
				'alt'      => '' !== $image_alt ? $image_alt : $alt,
				'width'    => $size,
				'height'   => $size,
				'sizes'    => $size . 'px',
				'loading'  => 'eager',
				'decoding' => 'async',
			)
		);
	}
	return '';
}
