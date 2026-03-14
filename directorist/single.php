<?php
/**
 * The template for displaying dynamic Directorist single listings.
 */

get_header( 'directorist' );

// Start the Loop
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$listing_id = get_the_ID();

		// Fetching Directorist Meta Data
		$address      = get_post_meta( $listing_id, '_address', true );
		$phone        = get_post_meta( $listing_id, '_phone', true );
		$email        = get_post_meta( $listing_id, '_email', true );
		$website      = get_post_meta( $listing_id, '_website', true );
		$tagline      = get_post_meta( $listing_id, '_tagline', true );
		$video_url    = get_post_meta( $listing_id, '_videourl', true );
		$is_featured  = get_post_meta( $listing_id, '_featured', true );
		$is_verified  = get_post_meta( $listing_id, '_claimed_by_admin', true );

		// Ratings calculation
		$average_rating = 0;
		if ( function_exists( 'directorist_get_listing_rating' ) ) {
			$average_rating = directorist_get_listing_rating( $listing_id );
		} else {
			$average_rating = get_post_meta( $listing_id, '_average_rating', true ) ? get_post_meta( $listing_id, '_average_rating', true ) : 0;
		}
		if ( function_exists( 'directorist_get_listing_review_count' ) ) {
			$review_count = directorist_get_listing_review_count( $listing_id );
		} else {
			$review_count = get_comments_number( $listing_id );
		}

		// Category Data
		$categories    = get_the_terms( $listing_id, ATBDP_CATEGORY );
		$category_name = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0]->name : '';

		// Location Data
		$locations  = get_the_terms( $listing_id, ATBDP_LOCATION );
		$loc_name   = ! empty( $locations ) && ! is_wp_error( $locations ) ? $locations[0]->name : '';

		// Tags
		$tags = get_the_terms( $listing_id, ATBDP_TAGS );

		// Overview Custom Fields (meta keys from functions.php)
		$year_founded      = get_post_meta( $listing_id, '_year_founded', true );
		$min_deposit       = get_post_meta( $listing_id, '_min_deposit', true );
		$execution_type    = get_post_meta( $listing_id, '_execution_type', true );
		$headquarters      = get_post_meta( $listing_id, '_headquarters', true );
		$max_leverage      = get_post_meta( $listing_id, '_max_leverage', true );
		$asset_classes     = get_post_meta( $listing_id, '_asset_classes', true );
		$regulation        = get_post_meta( $listing_id, '_regulation', true );
		$trading_platforms = get_post_meta( $listing_id, '_trading_platforms', true );
		$payment_methods   = get_post_meta( $listing_id, '_payment_methods', true );
		$publicly_listed   = get_post_meta( $listing_id, '_publicly_listed', true );
		$spreads_from      = get_post_meta( $listing_id, '_spreads_from', true );
		$countries         = get_post_meta( $listing_id, '_countries_served', true );

		// Editorial Review
		$editorial_review = get_post_meta( $listing_id, '_editorial_review', true );

		// Gallery Images
		$gallery_images = array();
		if ( function_exists( 'directorist_get_listing_gallery_images' ) ) {
			$gallery_images = directorist_get_listing_gallery_images( $listing_id );
		}
		if ( empty( $gallery_images ) ) {
			$listing_img = get_post_meta( $listing_id, '_listing_img', true );
			if ( ! empty( $listing_img ) ) {
				$gallery_images = is_array( $listing_img ) ? $listing_img : array( $listing_img );
			}
		}

		// Preview / Logo Image
		$logo_url = '';
		$prv_img_id = get_post_meta( $listing_id, '_listing_prv_img', true );
		if ( $prv_img_id && is_numeric( $prv_img_id ) ) {
			$logo_url = wp_get_attachment_image_url( (int) $prv_img_id, 'thumbnail' );
		} elseif ( function_exists( 'directorist_get_listing_preview_image' ) ) {
			$prv_id = directorist_get_listing_preview_image( $listing_id );
			if ( $prv_id ) {
				$logo_url = wp_get_attachment_image_url( $prv_id, 'thumbnail' );
			}
		}

		// Social Links (serialized array of {id, url})
		$social_links = get_post_meta( $listing_id, '_social', true );

		// Business Hours — '_disable_bz_hour_listing' is the correct plugin meta key.
		// A truthy value means the owner has explicitly disabled business hours for this listing.
		$disable_bz_hour = get_post_meta( $listing_id, '_disable_bz_hour_listing', true );
		$bdbh            = get_post_meta( $listing_id, '_bdbh', true );

		// FAQs (serialized array of {quez, ans})
		$faqs = get_post_meta( $listing_id, '_faqs', true );

		// Geo coordinates
		$manual_lat = get_post_meta( $listing_id, '_manual_lat', true );
		$manual_lng = get_post_meta( $listing_id, '_manual_lng', true );
		?>

		<body <?php body_class( 'pwdev-page-profile' ); ?>>

			<nav class="pwdev-breadcrumb" aria-label="Breadcrumb">
				<div class="pwdev-container">
					<ol class="pwdev-breadcrumb__list">
						<li class="pwdev-breadcrumb__item">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pwdev-breadcrumb__link">
								<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
									<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
								</svg>
							</a>
						</li>
						<li class="pwdev-breadcrumb__separator">›</li>
						<li class="pwdev-breadcrumb__item">
							<a href="<?php echo esc_url( get_post_type_archive_link( 'at_biz_dir' ) ); ?>" class="pwdev-breadcrumb__link">Directory</a>
						</li>
						<li class="pwdev-breadcrumb__separator">›</li>
						<li class="pwdev-breadcrumb__item">
							<a href="#" class="pwdev-breadcrumb__link"><?php echo esc_html( $category_name ); ?>s</a>
						</li>
						<li class="pwdev-breadcrumb__separator">›</li>
						<li class="pwdev-breadcrumb__item pwdev-breadcrumb__item--current"><?php the_title(); ?></li>
					</ol>
					<a href="javascript:history.back()" class="pwdev-breadcrumb__back">
						<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
							<path d="M19 12H5M12 19l-7-7 7-7"/>
						</svg>
						Back to Results
					</a>
				</div>
			</nav>

			<header class="pwdev-profile-hero">
				<div class="pwdev-profile-hero__banner">
					<?php 
					// Dynamic Banner image (Uses Listing Featured Image or fallback)
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'full', array( 'class' => 'pwdev-profile-hero__banner-img' ) );
					} else { ?>
						<img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1400&h=250&fit=crop" alt="Trading background" class="pwdev-profile-hero__banner-img">
					<?php } ?>
				</div>
				<div class="pwdev-container">
					<div class="pwdev-profile-hero__content">
						<div class="pwdev-profile-hero__logo-wrapper">
							<?php if ( $logo_url ) : ?>
								<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php the_title_attribute(); ?>" class="pwdev-profile-hero__logo" style="object-fit:cover;">
							<?php else : ?>
							<div class="pwdev-profile-hero__logo">
								<?php
								$words    = preg_split( '/\s+/', get_the_title() );
								$initials = '';
								if ( count( $words ) >= 2 ) {
									$initials = mb_strtoupper( mb_substr( $words[0], 0, 1 ) ) . mb_strtoupper( mb_substr( $words[1], 0, 1 ) );
								} else {
									$initials = mb_strtoupper( mb_substr( get_the_title(), 0, 2 ) );
								}
								echo esc_html( $initials );
								?>
							</div>
							<?php endif; ?>
						</div>
						<div class="pwdev-profile-hero__info">
							<div class="pwdev-profile-hero__title-row">
								<h1 class="pwdev-profile-hero__name"><?php the_title(); ?></h1>
								
								<?php if ( $is_verified ) : ?>
								<span class="pwdev-verification-badge pwdev-verification-badge--verified">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
										<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
									</svg>
									Verified
								</span>
								<?php endif; ?>

								<?php if ( $is_featured ) : ?>
								<span class="pwdev-verification-badge pwdev-verification-badge--featured">FEATURED</span>
								<?php endif; ?>
							</div>
							<div class="pwdev-profile-hero__meta">
								<div class="pwdev-profile-hero__rating">
									<div class="pwdev-card__stars">
										<?php 
										for ( $i = 1; $i <= 5; $i++ ) {
											$class = ( $i <= $average_rating ) ? '' : 'empty';
											$symbol = ( $i <= $average_rating ) ? '★' : '☆';
											echo '<span class="pwdev-card__star ' . $class . '">' . $symbol . '</span>';
										}
										?>
									</div>
									<span class="pwdev-profile-hero__reviews">(<?php echo esc_html( $review_count ); ?> reviews)</span>
								</div>
								<span class="pwdev-profile-hero__separator">·</span>
								<span class="pwdev-profile-hero__type"><?php echo esc_html( $category_name ); ?></span>
								<span class="pwdev-profile-hero__separator">·</span>
								<span class="pwdev-profile-hero__location">
									<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
									</svg>
									<?php echo esc_html( $address ); ?>
								</span>
							</div>
						</div>
						<div class="pwdev-profile-hero__actions">
							<button class="pwdev-btn pwdev-btn--primary pwdev-btn--lg">More Info</button>
							<button class="pwdev-btn pwdev-btn--icon" aria-label="Share">
								<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
									<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
								</svg>
							</button>
							<button class="pwdev-btn pwdev-btn--icon" aria-label="Bookmark">
								<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
									<path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
								</svg>
							</button>
							<button class="pwdev-btn pwdev-btn--icon" aria-label="Report">
								<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
									<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
								</svg>
							</button>
						</div>
					</div>
				</div>
			</header>

			<main class="pwdev-profile-content">
				<div class="pwdev-container">
					<div class="pwdev-profile-layout">
						<div class="pwdev-profile-main">
							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">Overview</h2>
								<div class="pwdev-overview-grid pwdev-overview-grid--with-icons">
									
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Year Founded</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $year_founded ) ? esc_html( $year_founded ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Min Deposit</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $min_deposit ) ? esc_html( $min_deposit ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Execution Type</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $execution_type ) ? esc_html( $execution_type ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Headquarters</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $headquarters ) ? esc_html( $headquarters ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Max Leverage</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $max_leverage ) ? esc_html( $max_leverage ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Asset Classes</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $asset_classes ) ? esc_html( $asset_classes ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Regulation</span>
										<?php if ( $regulation ) : ?>
											<div class="pwdev-overview-item__badges">
												<?php
												$reg_items = array_map( 'trim', explode( ',', $regulation ) );
												foreach ( $reg_items as $reg_item ) :
													$badge_slug = sanitize_title( $reg_item );
												?>
													<span class="pwdev-badge pwdev-badge--<?php echo esc_attr( $badge_slug ); ?>"><?php echo esc_html( $reg_item ); ?></span>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Trading Platforms</span>
										<?php if ( $trading_platforms ) : ?>
											<div class="pwdev-overview-item__badges">
												<?php
												$tp_items = array_map( 'trim', explode( ',', $trading_platforms ) );
												foreach ( $tp_items as $tp_item ) :
													$badge_slug = sanitize_title( $tp_item );
												?>
													<span class="pwdev-badge pwdev-badge--<?php echo esc_attr( $badge_slug ); ?>"><?php echo esc_html( $tp_item ); ?></span>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Payment Methods</span>
										<span class="pwdev-overview-item__value"><?php echo !empty( $payment_methods ) ? esc_html( $payment_methods ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Publicly Listed</span>
										<span class="pwdev-overview-item__value"><?php echo !empty( $publicly_listed ) ? esc_html( $publicly_listed ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Spreads From</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $spreads_from ) ? esc_html( $spreads_from ) : ''; ?></span>
										</div>
									</div>

									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Countries Served</span>
											<span class="pwdev-overview-item__value"><?php echo !empty( $countries ) ? esc_html( $countries ) : ''; ?></span>
										</div>
									</div>
								</div>
							</section>

							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">About <?php the_title(); ?></h2>
								<div class="pwdev-profile-section__content">
									<?php the_content(); ?>
								</div>
							</section>

							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title"><?php the_title(); ?> Review <?php echo date('Y'); ?></h2>
								<div class="pwdev-profile-section__meta">
									<span class="pwdev-author">By <strong><?php echo get_the_author(); ?></strong></span>
									<span class="separator">·</span>
									<span class="pwdev-date">Updated <?php the_modified_date('M j, Y'); ?></span>
									<span class="separator">·</span>
									<span class="pwdev-read-time">8 min read</span>
								</div>
								
								<div class="pwdev-review-content">
									<?php 
									if ( $editorial_review ) {
										echo apply_filters( 'the_content', $editorial_review );
									}
									?>
								</div>
							</section>

							<section class="pwdev-profile-section">
								<div class="pwdev-profile-section__header">
									<h2 class="pwdev-profile-section__title">Press Releases</h2>
									<a href="#" class="pwdev-profile-section__link">View All</a>
								</div>
								<div class="pwdev-press-releases">
									<article class="pwdev-press-release">
										<img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=80&h=80&fit=crop" alt="" class="pwdev-press-release__image">
										<div class="pwdev-press-release__content">
											<h4 class="pwdev-press-release__title"><?php the_title(); ?> Reports Record Q4 Revenue Amid Growing Retail Trading Volumes</h4>
											<div class="pwdev-press-release__meta">
												<span>Feb 15, 2025</span>
												<span class="pwdev-press-release__tag">Earnings</span>
											</div>
										</div>
									</article>
									<article class="pwdev-press-release">
										<img src="https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=80&h=80&fit=crop" alt="" class="pwdev-press-release__image">
										<div class="pwdev-press-release__content">
											<h4 class="pwdev-press-release__title"><?php the_title(); ?> Launches New ESG Indices for Sustainable Investing</h4>
											<div class="pwdev-press-release__meta">
												<span>Jan 30, 2025</span>
												<span class="pwdev-press-release__tag">Product</span>
											</div>
										</div>
									</article>
								</div>
							</section>

							<?php if ( ! empty( $gallery_images ) ) : ?>
							<section class="pwdev-profile-section">
								<div class="pwdev-profile-section__header">
									<h2 class="pwdev-profile-section__title">Screenshots & Media</h2>
									<span class="pwdev-profile-section__count pwdev-profile-section__count--red"><?php echo count( $gallery_images ); ?> images</span>
								</div>
								<div class="pwdev-media-gallery pwdev-media-gallery--split" id="mediaGallery">
									<?php
									// Determine if gallery contains attachment IDs or URLs
									$first_item = reset( $gallery_images );
									$is_ids     = is_numeric( $first_item );
									$first_full = $is_ids ? wp_get_attachment_image_url( (int) $first_item, 'large' ) : $first_item;
									?>
									<div class="pwdev-media-gallery__main">
										<img src="<?php echo esc_url( $first_full ); ?>" alt="Trading Platform Screenshot" class="pwdev-media-gallery__main-img" id="galleryMainImg">
									</div>
									<div class="pwdev-media-gallery__sidebar">
										<?php foreach ( $gallery_images as $gallery_item ) :
											if ( $is_ids ) {
												$thumb = wp_get_attachment_image_url( (int) $gallery_item, 'thumbnail' );
												$full  = wp_get_attachment_image_url( (int) $gallery_item, 'large' );
											} else {
												$thumb = $gallery_item;
												$full  = $gallery_item;
											}
										?>
										<div class="pwdev-media-gallery__thumb" data-full="<?php echo esc_url( $full ); ?>">
											<img src="<?php echo esc_url( $thumb ); ?>" alt="Screenshot">
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( $video_url ) : ?>
							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">Broker Overview Video</h2>
								<div class="pwdev-video-container pwdev-video-container--fullwidth">
									<div class="pwdev-video-embed">
										<?php 
										// Basic conversion of Youtube URL to Embed
										$embed_url = str_replace("watch?v=", "embed/", $video_url);
										?>
										<iframe 
											src="<?php echo esc_url($embed_url); ?>" 
											title="<?php the_title(); ?> Platform Tour"
											frameborder="0" 
											allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
											allowfullscreen>
										</iframe>
									</div>
									<p class="pwdev-video-caption">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
										</svg>
										<?php the_title(); ?> Platform Tour & Trading Features
										<a href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener" class="pwdev-video-link">Watch on YouTube</a>
									</p>
								</div>
							</section>
							<?php endif; ?>

							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">Reviews & Ratings</h2>
								<div class="pwdev-reviews-summary pwdev-reviews-summary--left">
									<div class="pwdev-reviews-summary__stars">
										<div class="pwdev-card__stars pwdev-card__stars--lg">
											<?php 
											for ( $i = 1; $i <= 5; $i++ ) {
												$class = ( $i <= $average_rating ) ? '' : 'empty';
												echo '<span class="pwdev-card__star ' . $class . '">★</span>';
											}
											?>
										</div>
									</div>
									<span class="pwdev-reviews-summary__count">Based on <?php echo esc_html( $review_count ); ?> Reviews</span>
								</div>
							</section>

							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">Location</h2>
								<div class="pwdev-location-map">
									<div class="pwdev-location-map__embed">
										<iframe 
											src="https://maps.google.com/maps?q=<?php echo urlencode($address); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" 
											width="100%" 
											height="300" 
											style="border:0; border-radius: 8px;" 
											allowfullscreen="" 
											loading="lazy" 
											referrerpolicy="no-referrer-when-downgrade">
										</iframe>
									</div>
									<div class="pwdev-location-details">
										<p class="pwdev-location-details__address">
											<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
											</svg>
											<?php echo esc_html( $address ); ?>
										</p>
										<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($address); ?>" target="_blank" rel="noopener" class="pwdev-location-details__link">
											Open in Google Maps
											<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
												<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
											</svg>
										</a>
									</div>
								</div>
							</section>

							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">Frequently Asked Questions</h2>
								<div class="pwdev-faq-list">
									<?php if ( ! empty( $faqs ) && is_array( $faqs ) ) : ?>
										<?php foreach ( $faqs as $faq_item ) :
											$faq_question = isset( $faq_item['quez'] ) ? $faq_item['quez'] : '';
											$faq_answer   = isset( $faq_item['ans'] ) ? $faq_item['ans'] : '';
											if ( empty( $faq_question ) ) continue;
										?>
										<div class="pwdev-faq-item">
											<button class="pwdev-faq-item__question">
												<span><?php echo esc_html( $faq_question ); ?></span>
												<svg class="pwdev-faq-item__icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
													<path d="m6 9 6 6 6-6"/>
												</svg>
											</button>
											<div class="pwdev-faq-item__answer">
												<p><?php echo esc_html( $faq_answer ); ?></p>
											</div>
										</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</section>
						</div>

						<aside class="pwdev-profile-sidebar">
							<div class="pwdev-sidebar-card">
								<h3 class="pwdev-sidebar-card__title">Contact Information</h3>
								<ul class="pwdev-contact-list">
									<?php if ( $phone ) : ?>
									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
											<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
										</svg>
										<span><?php echo esc_html( $phone ); ?></span>
									</li>
									<?php endif; ?>

									<?php if ( $email ) : ?>
									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
											<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
										</svg>
										<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
									</li>
									<?php endif; ?>

									<?php if ( $website ) : ?>
									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
											<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
										</svg>
										<a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener"><?php echo esc_html( parse_url($website, PHP_URL_HOST) ); ?></a>
									</li>
									<?php endif; ?>

									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
											<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
										</svg>
										<span><?php echo esc_html( $address ); ?></span>
									</li>
								</ul>
								<?php if ( $website ) : ?>
								<a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener" class="pwdev-btn pwdev-btn--dark pwdev-btn--block">
									<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
									</svg>
									Visit Website
								</a>
								<?php endif; ?>
							</div>

							<div class="pwdev-sidebar-card">
								<h3 class="pwdev-sidebar-card__title">Social & Messengers</h3>
								<?php 
								// Directorist stores social as a serialized array of {id, url}
								if ( ! empty( $social_links ) && is_array( $social_links ) ) : ?>
									<h4 class="pwdev-sidebar-card__subtitle pwdev-sidebar-card__subtitle--first">Social Media</h4>
									<div class="pwdev-social-links">
										<?php foreach ( $social_links as $social_item ) :
											$social_id  = isset( $social_item['id'] ) ? $social_item['id'] : '';
											$social_url = isset( $social_item['url'] ) ? $social_item['url'] : '';
											if ( empty( $social_id ) || empty( $social_url ) ) continue;
										?>
											<a href="<?php echo esc_url( $social_url ); ?>" class="pwdev-social-link pwdev-social-link--neutral" aria-label="<?php echo esc_attr( $social_id ); ?>" target="_blank" rel="noopener">
												<i class="fab fa-<?php echo esc_attr( $social_id ); ?>"></i>
											</a>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php if ( ! $disable_bz_hour ) : ?>							<div class="pwdev-sidebar-card">
								<div class="pwdev-sidebar-card__header">
									<h3 class="pwdev-sidebar-card__title">Business Hours</h3>
									<?php
									$days_of_week = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
									$is_open_now  = false;

									if ( ! empty( $bdbh ) && is_array( $bdbh ) ) {
										$current_day  = strtolower( wp_date( 'l' ) );
										$current_time = wp_date( 'H:i' );
										if ( isset( $bdbh[ $current_day ] ) ) {
											$day_data = $bdbh[ $current_day ];
											if ( ! empty( $day_data['enable'] ) && $day_data['enable'] === 'enable' ) {
												$start = isset( $day_data['start'][0] ) ? $day_data['start'][0] : '';
												$close = isset( $day_data['close'][0] ) ? $day_data['close'][0] : '';
												if ( $start && $close && $current_time >= $start && $current_time <= $close ) {
													$is_open_now = true;
												}
											}
										}
									}
									?>
									<?php if ( $is_open_now ) : ?>
										<span class="pwdev-status-indicator pwdev-status-indicator--available"><span class="pwdev-status-dot"></span>Open Now</span>
									<?php else : ?>
										<span class="pwdev-status-indicator"><span class="pwdev-status-dot"></span>Closed</span>
									<?php endif; ?>
								</div>
								<ul class="pwdev-hours-list">
									<?php if ( ! empty( $bdbh ) && is_array( $bdbh ) ) : ?>
										<?php foreach ( $days_of_week as $day ) :
											$day_data   = isset( $bdbh[ $day ] ) ? $bdbh[ $day ] : array();
											$is_enabled = ( ! empty( $day_data['enable'] ) && $day_data['enable'] === 'enable' );
											$start_time = ( $is_enabled && ! empty( $day_data['start'][0] ) ) ? $day_data['start'][0] : '';
											$close_time = ( $is_enabled && ! empty( $day_data['close'][0] ) ) ? $day_data['close'][0] : '';
										?>
										<li class="pwdev-hours-list__item">
											<span><?php echo esc_html( ucfirst( $day ) ); ?></span>
											<?php if ( $is_enabled && $start_time && $close_time ) : ?>
												<span><?php echo esc_html( $start_time . ' - ' . $close_time ); ?></span>
											<?php else : ?>
												<span class="pwdev-hours-list__closed">Closed</span>
											<?php endif; ?>
										</li>
										<?php endforeach; ?>
									<?php endif; ?>
								</ul>
							</div>						<?php endif; ?>
							<div class="pwdev-sidebar-card">
								<h3 class="pwdev-sidebar-card__title">Contact <?php the_title(); ?></h3>
								<?php 
								// Best practice: Use a Directorist shortcode for the contact form
								// Example: echo do_shortcode('[directorist_contact_listing_form]'); 
								?>
								<form class="pwdev-contact-form">
									<div class="pwdev-form-group">
										<label class="pwdev-form-label">Your Name</label>
										<input type="text" class="pwdev-form-input" placeholder="John Smith">
									</div>
									<div class="pwdev-form-group">
										<label class="pwdev-form-label">Email Address</label>
										<input type="email" class="pwdev-form-input" placeholder="john@example.com">
									</div>
									<div class="pwdev-form-group">
										<label class="pwdev-form-label">Message</label>
										<textarea class="pwdev-form-textarea" rows="4" placeholder="Your message..."></textarea>
									</div>
									<button type="submit" class="pwdev-btn pwdev-btn--success pwdev-btn--block">Send Message</button>
								</form>
							</div>

							<div class="pwdev-sidebar-card pwdev-sidebar-card--claim">
								<div class="pwdev-sidebar-card__icon">
									<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
										<path d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
									</svg>
								</div>
								<h3 class="pwdev-sidebar-card__title pwdev-sidebar-card__title--center">Is this your listing?</h3>
								<p class="pwdev-sidebar-card__text pwdev-sidebar-card__text--center">Claim it to update information and respond to reviews.</p>
								<a href="<?php echo esc_url( add_query_arg( 'claim', $listing_id, get_post_type_archive_link('at_biz_dir') ) ); ?>" class="pwdev-btn pwdev-btn--outline-primary pwdev-btn--block">Claim This Listing</a>
							</div>
						</aside>
					</div>
				</div>
			</main>

			<script>
				document.querySelectorAll('.pwdev-faq-item__question').forEach(button => {
					button.addEventListener('click', () => {
						const item = button.parentElement;
						item.classList.toggle('pwdev-faq-item--expanded');
					});
				});

				document.querySelectorAll('.pwdev-media-gallery__thumb').forEach(thumb => {
					thumb.addEventListener('click', () => {
						const gallery = thumb.closest('.pwdev-media-gallery');
						const mainImg = gallery.querySelector('.pwdev-media-gallery__main-img');
						const fullSrc = thumb.dataset.full;
						if (fullSrc && mainImg) {
							mainImg.src = fullSrc;
							gallery.querySelectorAll('.pwdev-media-gallery__thumb').forEach(t => t.classList.remove('pwdev-media-gallery__thumb--active'));
							thumb.classList.add('pwdev-media-gallery__thumb--active');
						}
					});
				});
			</script>

		<?php
	endwhile;
endif;

get_footer();
?>