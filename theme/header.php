<?php
/**
 * The template for displaying the header.
 *
 * @package CTCL\ElectionWebsite
 * @since 1.0.0
 */

$logo_id    = get_theme_mod( 'custom_logo' );
$site_title = get_bloginfo( 'title' );

if ( is_front_page() ) {
	$banner_enabled = \CTCL\ElectionWebsite\Banner::is_enabled();
	$banner_title = $banner_enabled ? \CTCL\ElectionWebsite\Banner::title() : '';
} else {
	$banner_enabled = \CTCL\ElectionWebsite\Alert_Banner::is_enabled();
	$banner_title = $banner_enabled ? \CTCL\ElectionWebsite\Alert_Banner::title() : '';
}
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
<?php if ( is_front_page() ) : ?>

	<?php if ( $banner_enabled && $banner_title ) : ?>
	<section class="banner major">
		<div class="banner-wrapper">
			<div>
				<h2><?php echo esc_html( $banner_title ); ?></h2>
				<p><?php echo esc_html( \CTCL\ElectionWebsite\Banner::description() ); ?></p>
				<?php $link = \CTCL\ElectionWebsite\Banner::link(); ?>
				<?php if ( $link ): ?>
				<p><a class="button learn-more" href="<?php echo esc_url( $link ); ?>">Learn More</a></p>
				<?php endif; ?>
			</div>
			<?php
			$banner_id = \CTCL\ElectionWebsite\Banner::image_id();
			if ( $banner_id ) {
				echo wp_kses_post( wp_get_attachment_image( $banner_id, 'banner' ) );
			}
			?>
		</div>
	</section>
	<?php endif; ?>

<?php else : ?>
	<?php if ( $banner_enabled && $banner_title ) : ?>
	<section class="banner alert">
		<div class="banner-wrapper">
			<p>
				<b><?php echo esc_html( $banner_title ); ?></b>
				/
				<?php
					echo esc_html( \CTCL\ElectionWebsite\Alert_Banner::description() );
					$link = \CTCL\ElectionWebsite\Alert_Banner::link();
				?>
				<?php if ( $link ): ?>
				<a class="alert learn-more" href="<?php echo esc_url( $link ); ?>">Learn More</a>
				<?php endif; ?>
			</p>
		</div>
	</section>
	<?php endif; ?>

<?php endif; ?>
