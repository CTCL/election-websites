<?php
/**
 * The template for displaying the header.
 *
 * @package CTCL\ElectionWebsite
 * @since 1.0.0
 */

$logo_id    = get_option( 'site_icon' );
$site_title = get_bloginfo( 'title' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>
	<a class="site-title-wrapper" href="<?php echo esc_url( get_home_url() ); ?>">
		<?php
		echo wp_kses_post( wp_get_attachment_image( $logo_id, 'header-icon', false, [ 'alt' => $site_title ] ) );
		?>
		<h4 class="site-title"><?php echo esc_html( $site_title ); ?></h4>
	</a>
	<?php
		wp_nav_menu(
			[
				'theme_location' => 'header-menu',
			]
		);
		?>
</header>
<?php
if ( is_front_page() ) {
	?>
<section class="banner major">
	<div>
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/envelope.png' ); ?>" alr="Envelope" width="195" height="93" />

		<h2>Vote by Mail in Upcoming Elections</h2>
		<p>To help prevent the community spread of COVID-19, all registered, eligible voters may apply to vote by mail ballot. Learn more</p>
	</div>
</section>
<?php } else { ?>
<section class="banner">
	<div>
		<p><b>Important Information Banner</b> / Really important info if you need it on all pages <a>Learn more</a></p>
	</div>
</section>
<?php } ?>
