<?php
/**
 * The template for displaying the header.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

$logo_id       = get_theme_mod( 'custom_logo' );
$site_title    = \CTCL\Elections\Office_Details::title();
$is_front_page = is_front_page();
if ( $is_front_page ) {
	$banner_enabled = \CTCL\Elections\Banner::is_enabled();
	$banner_title   = \CTCL\Elections\Banner::title();
}

$alert_banner_enabled = \CTCL\Elections\Alert_Banner::is_enabled();
$alert_banner_title   = \CTCL\Elections\Alert_Banner::title();
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
	<div class="header-wrapper">
		<a class="site-title-wrapper" href="<?php echo esc_url( get_home_url() ); ?>" tabindex="-1">
			<?php
			echo wp_kses_post( wp_get_attachment_image( $logo_id, 'header-icon', false ) );
			?>

			<?php if ( is_front_page() ) : ?>
			<h1 class="site-title"><?php echo esc_html( $site_title ); ?></h1>
			<?php else : ?>
			<p class="site-title"><?php echo esc_html( $site_title ); ?></p>
			<?php endif; ?>
			<span class="mobile-menu icon-bars" aria-expanded="false" tabindex="0">
				<span class="icon-bar bar-top"></span>
				<span class="icon-bar bar-middle"></span>
				<span class="icon-bar bar-bottom"></span>
			</span>
		</a>
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'header-menu',
				'container'      => 'nav',
			]
		);
		?>
	</div>
</header>

<?php if ( $alert_banner_enabled && $alert_banner_title ) : ?>
<section class="banner alert">
	<div class="banner-wrapper">
		<p>
			<b><?php echo esc_html( $alert_banner_title ); ?></b>
			<span class="divider">/</span>
			<?php
				echo esc_html( \CTCL\Elections\Alert_Banner::description() );
				$alert_link = \CTCL\Elections\Alert_Banner::link();
				$alert_text = \CTCL\Elections\Alert_Banner::link_text();
			?>
			<?php if ( $alert_link && $alert_text ) : ?>
			<a class="alert learn-more" href="<?php echo esc_url( $alert_link ); ?>"><?php echo esc_html( $alert_text ); ?></a>
			<?php endif; ?>
		</p>
	</div>
</section>
<?php endif; ?>

<?php if ( $is_front_page && $banner_enabled && $banner_title ) : ?>
<section class="banner major <?php echo sanitize_html_class( \CTCL\Elections\Elections_Settings::get_banner_style() ); ?>">
	<div class="banner-wrapper">
		<div>
			<h2><?php echo esc_html( $banner_title ); ?></h2>
			<p><?php echo esc_html( \CTCL\Elections\Banner::description() ); ?></p>
			<?php
				$banner_link   = \CTCL\Elections\Banner::link();
				$banner_button = \CTCL\Elections\Banner::button();
			?>
			<?php if ( $banner_link && $banner_button ) : ?>
			<p class="learn-more"><a class="button learn-more" href="<?php echo esc_url( $banner_link ); ?>"><?php echo esc_html( $banner_button ); ?></a></p>
			<?php endif; ?>
		</div>
		<?php
		$banner_id = \CTCL\Elections\Banner::image_id();
		if ( $banner_id ) {
			echo wp_kses_post( \CTCL\Elections\Helpers::inline_image( $banner_id, 'banner' ) );
		}
		?>
	</div>
</section>
<?php endif; ?>
