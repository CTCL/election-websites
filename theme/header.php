<?php
/**
 * The template for displaying the header.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

$logo_id    = get_theme_mod( 'custom_logo' );
$site_title = \CTCL\Elections\Office_Details::title();

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
	</div>
</header>

<?php if ( $alert_banner_enabled && $alert_banner_title ) : ?>
<section class="banner alert">
	<div class="banner-wrapper">
		<p>
			<b><?php echo esc_html( $alert_banner_title ); ?></b>
			/
			<?php
				echo esc_html( \CTCL\Elections\Alert_Banner::description() );
				$learn_more = \CTCL\Elections\Alert_Banner::link();
			?>
			<?php if ( $learn_more ) : ?>
			<a class="alert learn-more" href="<?php echo esc_url( $learn_more ); ?>">Learn More</a>
			<?php endif; ?>
		</p>
	</div>
</section>
<?php endif; ?>

<?php if ( $is_front_page && $banner_enabled && $banner_title ) : ?>
<section class="banner major">
	<div class="banner-wrapper">
		<div>
			<h2><?php echo esc_html( $banner_title ); ?></h2>
			<p><?php echo esc_html( \CTCL\Elections\Banner::description() ); ?></p>
			<?php $learn_more = \CTCL\Elections\Banner::link(); ?>
			<?php if ( $learn_more ) : ?>
			<p class="learn-more"><a class="button learn-more" href="<?php echo esc_url( $learn_more ); ?>">Learn More</a></p>
			<?php endif; ?>
		</div>
		<?php
		$banner_id = \CTCL\Elections\Banner::image_id();
		if ( $banner_id ) {
			echo wp_kses_post( wp_get_attachment_image( $banner_id, 'banner' ) );
		}
		?>
	</div>
</section>
<?php endif; ?>
