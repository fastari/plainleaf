<?php
/**
 * Title: About page: starter
 * Slug: plainleaf/about-starter
 * Categories: plainleaf-pages, about
 * Block Types: core/post-content
 * Post Types: page
 * Description: A full About page to edit: a short intro, what you write about, who it's for, a bit about you, and how to get in touch. Replace the example text with your own.
 * Keywords: about, bio, introduction, author
 * Viewport Width: 1200
 *
 * @package Plainleaf
 */

?>
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php esc_html_e( 'Hi, I’m Sam. I write about slow productivity, simple tools, and the small habits that make creative work feel lighter.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'This blog started as a paper notebook: half-finished ideas, lessons from projects that went sideways, and quotes I didn’t want to forget. Writing them here in public keeps me honest, and every so often a note helps someone else get unstuck.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'New posts go out about twice a month. No hustle, no hacks: just what I’m learning, written as plainly as I can.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'What you’ll find here', 'plainleaf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list">
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Notes:', 'plainleaf' ); ?></strong> <?php esc_html_e( 'short thoughts and things learned along the way.', 'plainleaf' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Essays:', 'plainleaf' ); ?></strong> <?php esc_html_e( 'longer pieces that follow one idea all the way through.', 'plainleaf' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Projects:', 'plainleaf' ); ?></strong> <?php esc_html_e( 'things I’m building, with the decisions and mistakes behind them.', 'plainleaf' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong><?php esc_html_e( 'Reading:', 'plainleaf' ); ?></strong> <?php esc_html_e( 'books and articles worth your time, and the one idea I took from each.', 'plainleaf' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Who it’s for', 'plainleaf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Writers, makers, and anyone with more ideas than time. If you’ve ever closed a laptop full of open tabs and felt like nothing got done, you’re in the right place.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'A little about me', 'plainleaf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Before writing here, I spent ten years in product design, working with small teams on apps you’ve probably never heard of. I’ve shipped things I’m proud of and a few I’d rather forget, and I learned the most from the second kind.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'These days I live in a small town with a patient partner, a loud dog, and too many notebooks. Most mornings start with coffee and a blank page.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Write it down first. Make it good later.', 'plainleaf' ); ?></p>
<!-- /wp:paragraph -->
</blockquote>
<!-- /wp:quote -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Say hello', 'plainleaf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>
<?php
printf(
	/* translators: 1: link to the newsletter page, 2: link to the Start here page. */
	esc_html__( 'The best way to reach me is to reply to %1$s: every reply lands in my inbox and I read them all. New here? %2$s has the posts I’d read first.', 'plainleaf' ),
	'<a href="' . esc_url( home_url( '/newsletter/' ) ) . '">' . esc_html__( 'the newsletter', 'plainleaf' ) . '</a>',
	'<a href="' . esc_url( home_url( '/start-here/' ) ) . '">' . esc_html__( 'Start here', 'plainleaf' ) . '</a>'
);
?>
</p>
<!-- /wp:paragraph -->
