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
		$directorist_listing = class_exists( '\\Directorist\\Directorist_Single_Listing' )
			? \Directorist\Directorist_Single_Listing::instance( (int) $listing_id )
			: null;

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
		$normalized_rating = max( 0, min( 5, (float) $average_rating ) );

		// Category Data
		$categories = get_the_terms( $listing_id, ATBDP_CATEGORY );
		if ( empty( $categories ) || is_wp_error( $categories ) ) {
			$categories = get_the_terms( $listing_id, 'at_biz_dir-category' );
		}
		$category_name = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0]->name : '';
		$category_url  = ! empty( $categories ) && ! is_wp_error( $categories ) ? get_term_link( $categories[0] ) : '';

		$post_type_object = get_post_type_object( get_post_type( $listing_id ) );
		$directory_label  = ( $post_type_object && ! empty( $post_type_object->labels->name ) ) ? $post_type_object->labels->name : __( 'Directory', 'theme-reporter-child' );
		$directory_url    = get_post_type_archive_link( 'at_biz_dir' );

		// Location Data
		$locations = get_the_terms( $listing_id, ATBDP_LOCATION );
		if ( empty( $locations ) || is_wp_error( $locations ) ) {
			$locations = get_the_terms( $listing_id, 'at_biz_dir-location' );
		}
		$loc_name = '';
		if ( ! empty( $locations ) && ! is_wp_error( $locations ) ) {
			$loc_name = $locations[0]->name;
			foreach ( $locations as $location_term ) {
				if ( 'bali' === strtolower( $location_term->slug ) || 'bali' === strtolower( $location_term->name ) ) {
					$loc_name = $location_term->name;
					break;
				}
			}
		}
		$display_location = $loc_name ? $loc_name : $address;

		// Tags
		$tags = get_the_terms( $listing_id, ATBDP_TAGS );

		// Overview fields: ignore auto-import placeholders and prefer real custom field values.
		$placeholder_values = array(
			'deposit', 'from', 'spreads', 'regulation', 'platforms', 'methods',
			'founded', 'execution', 'headquarters', 'leverage', 'classes',
			'publicly listed', 'served'
		);

		$is_placeholder_value = static function( $value ) use ( $placeholder_values ) {
			$normalized = strtolower( trim( wp_strip_all_tags( (string) $value ) ) );
			return $normalized === '' || in_array( $normalized, $placeholder_values, true );
		};

		$get_first_valid_meta = static function( $keys ) use ( $listing_id, $is_placeholder_value ) {
			foreach ( $keys as $meta_key ) {
				$meta_val = get_post_meta( $listing_id, $meta_key, true );
				$meta_val = maybe_unserialize( $meta_val );

				if ( is_array( $meta_val ) || is_object( $meta_val ) ) {
					continue;
				}

				$value = trim( wp_strip_all_tags( (string) $meta_val ) );
				if ( $is_placeholder_value( $value ) ) {
					continue;
				}

				return $value;
			}

			return '';
		};

		$get_list_meta = static function( $keys ) use ( $listing_id, $is_placeholder_value ) {
			$results = array();

			foreach ( $keys as $meta_key ) {
				$meta_val = get_post_meta( $listing_id, $meta_key, true );
				$meta_val = maybe_unserialize( $meta_val );

				if ( is_array( $meta_val ) ) {
					$items = $meta_val;
				} else {
					$items = explode( ',', (string) $meta_val );
				}

				foreach ( $items as $item ) {
					$label = trim( wp_strip_all_tags( (string) $item ) );
					if ( $is_placeholder_value( $label ) ) {
						continue;
					}
					$results[ $label ] = $label;
				}
			}

			return array_values( $results );
		};

		$year_founded        = $get_first_valid_meta( array( '_year_founded', '_custom-number' ) );
		$min_deposit         = $get_first_valid_meta( array( '_custom-number-3', '_min_deposit' ) );
		$execution_type      = $get_first_valid_meta( array( '_execution_type', '_custom-select-3' ) );
		$headquarters        = $get_first_valid_meta( array( '_headquarters', '_custom-text' ) );
		$max_leverage        = $get_first_valid_meta( array( '_max_leverage', '_custom-text-3' ) );
		$publicly_listed     = $get_first_valid_meta( array( '_publicly_listed', '_custom-select-2' ) );
		$spreads_from_items  = $get_list_meta( array( '_custom-checkbox-6', '_spreads_from', '_custom-text-5' ) );
		$spreads_from        = ! empty( $spreads_from_items ) ? $spreads_from_items[0] : '';
		$asset_classes_items = $get_list_meta( array( '_asset_classes', '_custom-checkbox-3' ) );
		$regulation_items    = $get_list_meta( array( '_regulation', '_custom-checkbox' ) );
		$platform_items      = $get_list_meta( array( '_trading_platforms', '_custom-checkbox-2' ) );
		$payment_items       = $get_list_meta( array( '_payment_methods', '_custom-checkbox-4' ) );
		$account_types_items = $get_list_meta( array( '_account_types', '_custom-checkbox-5' ) );
		$countries_items     = $get_list_meta( array( '_countries_served' ) );

		$asset_classes     = ! empty( $asset_classes_items ) ? implode( ', ', $asset_classes_items ) : '';
		$payment_methods   = ! empty( $payment_items ) ? implode( ', ', $payment_items ) : '';
		$countries         = ! empty( $countries_items ) ? implode( ', ', $countries_items ) : '';
		$regulation        = ! empty( $regulation_items ) ? implode( ', ', $regulation_items ) : '';
		$trading_platforms = ! empty( $platform_items ) ? implode( ', ', $platform_items ) : '';
		$account_types     = ! empty( $account_types_items ) ? implode( ', ', $account_types_items ) : '';

		$min_deposit_num = str_replace( array( '$', ',', ' ' ), '', $min_deposit );
		if ( $min_deposit !== '' && is_numeric( $min_deposit_num ) ) {
			$min_deposit = '$' . number_format_i18n( (float) $min_deposit_num );
		}

		$spreads_num = str_replace( array( ',', ' ' ), '', $spreads_from );
		if ( $spreads_from !== '' && is_numeric( $spreads_num ) && stripos( $spreads_from, 'pip' ) === false ) {
			$spreads_fmt  = rtrim( rtrim( number_format( (float) $spreads_num, 2, '.', '' ), '0' ), '.' );
			$spreads_from = $spreads_fmt . ' pips';
		}

		// Editorial sections from listing form fields.
		$overview_content           = get_post_meta( $listing_id, '_custom-textarea', true );
		$trading_experience_content = get_post_meta( $listing_id, '_custom-textarea-3', true );
		$fees_pricing_content       = get_post_meta( $listing_id, '_custom-textarea-4', true );
		$editorial_review           = get_post_meta( $listing_id, '_editorial_review', true );

		if ( ! is_string( $overview_content ) ) {
			$overview_content = '';
		}
		if ( ! is_string( $trading_experience_content ) ) {
			$trading_experience_content = '';
		}
		if ( ! is_string( $fees_pricing_content ) ) {
			$fees_pricing_content = '';
		}

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
		$linkedin_url = '';
		$twitter_url  = '';
		$facebook_url = '';
		$youtube_url  = '';
		$instagram_url = '';
		$whatsapp_url = '';
		$telegram_url = '';
		$skype_url    = '';

		if ( ! empty( $social_links ) && is_array( $social_links ) ) {
			foreach ( $social_links as $social_item ) {
				$social_id  = isset( $social_item['id'] ) ? sanitize_key( $social_item['id'] ) : '';
				$social_url = isset( $social_item['url'] ) ? $social_item['url'] : ( isset( $social_item['content'] ) ? $social_item['content'] : '' );
				$social_url = esc_url_raw( $social_url );

				if ( empty( $social_id ) || empty( $social_url ) ) {
					continue;
				}

				switch ( $social_id ) {
					case 'linkedin':
						$linkedin_url = $social_url;
						break;
					case 'twitter':
					case 'x':
						$twitter_url = $social_url;
						break;
					case 'facebook':
						$facebook_url = $social_url;
						break;
					case 'youtube':
						$youtube_url = $social_url;
						break;
					case 'instagram':
						$instagram_url = $social_url;
						break;
					case 'whatsapp':
						$whatsapp_url = $social_url;
						break;
					case 'telegram':
						$telegram_url = $social_url;
						break;
					case 'skype':
						$skype_url = $social_url;
						break;
				}
			}
		}

		// Business Hours - '_disable_bz_hour_listing' is the correct plugin meta key.
		// A truthy value means the owner has explicitly disabled business hours for this listing.
		$disable_bz_hour = get_post_meta( $listing_id, '_disable_bz_hour_listing', true );
		$bdbh            = get_post_meta( $listing_id, '_bdbh', true );

		// FAQs (serialized array of {quez, ans})
		$faqs = get_post_meta( $listing_id, '_faqs', true );

		// Geo coordinates
		$manual_lat = get_post_meta( $listing_id, '_manual_lat', true );
		$manual_lng = get_post_meta( $listing_id, '_manual_lng', true );
		?>

			<nav class="pwdev-breadcrumb" aria-label="Breadcrumb">
				<div class="pwdev-container">
					<ol class="pwdev-breadcrumb__list">
						<li class="pwdev-breadcrumb__item">
							<a href="/all-listings/" class="pwdev-breadcrumb__link"><?php echo esc_html( 'All Listings' ); ?></a>
						</li>
						<li class="pwdev-breadcrumb__separator">&rsaquo;</li>
						<li class="pwdev-breadcrumb__item">
							<?php if ( $category_name && $category_url && ! is_wp_error( $category_url ) ) : ?>
								<a href="<?php echo esc_url( $category_url ); ?>" class="pwdev-breadcrumb__link"><?php echo esc_html( $category_name ); ?></a>
							<?php else : ?>
								<span class="pwdev-breadcrumb__link"><?php echo esc_html( $category_name ); ?></span>
							<?php endif; ?>
						</li>
						<li class="pwdev-breadcrumb__separator">&rsaquo;</li>
						<li class="pwdev-breadcrumb__item pwdev-breadcrumb__item--current"><?php the_title(); ?></li>
					</ol>
					<a href="javascript:history.back()" class="pwdev-breadcrumb__back">
						<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
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
									<svg width="14" height="14" viewBox="0 0 24 24" fill="#111827">
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
											$class = ( $i <= $normalized_rating ) ? '' : 'empty';
											$symbol = ( $i <= $normalized_rating ) ? '&#9733;' : '&#9734;';
											echo '<span class="pwdev-card__star ' . $class . '">' . $symbol . '</span>';
										}
										?>
									</div>
									<span class="pwdev-profile-hero__reviews">(<?php echo esc_html( $review_count ); ?> reviews)</span>
								</div>
								<span class="pwdev-profile-hero__separator">&middot;</span>
								<span class="pwdev-profile-hero__type"><?php echo esc_html( $category_name ); ?></span>
								<span class="pwdev-profile-hero__separator">&middot;</span>
								<span class="pwdev-profile-hero__location">
									<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
										<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
									</svg>
									<?php echo esc_html( $display_location ); ?>
								</span>
							</div>
						</div>
						<div class="pwdev-profile-hero__actions">
							<span class="pwdev-profile-hero__actions-label">More Info</span>
							<div class="pwdev-directorist-actions-wrap">
								<?php
								$listing = $directorist_listing;
								$data    = array( 'label' => __( 'Share', 'directorist' ) );
								$icon    = 'las la-share-square';
								include get_stylesheet_directory() . '/directorist/single/fields/share.php';

								$data    = array( 'label' => __( 'Bookmark', 'directorist' ) );
								$icon    = 'las la-bookmark';
								include get_stylesheet_directory() . '/directorist/single/fields/bookmark.php';

								$data    = array( 'label' => __( 'Report', 'directorist' ) );
								$icon    = 'las la-flag';
								include get_stylesheet_directory() . '/directorist/single/fields/report.php';
								?>
							</div>
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
									
									<?php if ( $year_founded ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Year Founded</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $year_founded ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $min_deposit ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Min Deposit</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $min_deposit ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $execution_type ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Execution Type</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $execution_type ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $headquarters ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Headquarters</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $headquarters ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $max_leverage ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Max Leverage</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $max_leverage ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $asset_classes ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Asset Classes</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $asset_classes ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( ! empty( $regulation_items ) ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Regulation</span>
											<div class="pwdev-overview-item__badges">
												<?php
												foreach ( $regulation_items as $reg_item ) :
													$badge_slug = sanitize_title( $reg_item );
												?>
													<span class="pwdev-badge pwdev-badge--<?php echo esc_attr( $badge_slug ); ?>"><?php echo esc_html( $reg_item ); ?></span>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( ! empty( $platform_items ) ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Trading Platforms</span>
											<div class="pwdev-overview-item__badges">
												<?php
												foreach ( $platform_items as $tp_item ) :
													$badge_slug = sanitize_title( $tp_item );
												?>
													<span class="pwdev-badge pwdev-badge--<?php echo esc_attr( $badge_slug ); ?>"><?php echo esc_html( $tp_item ); ?></span>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $payment_methods ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Payment Methods</span>
										<span class="pwdev-overview-item__value"><?php echo esc_html( $payment_methods ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( ! empty( $account_types_items ) ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<path d="M3 7h18M3 12h18M3 17h18"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Account Types</span>
											<div class="pwdev-overview-item__badges">
												<?php foreach ( $account_types_items as $account_type_item ) : ?>
													<span class="pwdev-badge pwdev-badge--<?php echo esc_attr( sanitize_title( $account_type_item ) ); ?>"><?php echo esc_html( $account_type_item ); ?></span>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $publicly_listed ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Publicly Listed</span>
										<span class="pwdev-overview-item__value"><?php echo esc_html( $publicly_listed ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $spreads_from ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Spreads From</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $spreads_from ); ?></span>
										</div>
									</div>
									<?php endif; ?>

									<?php if ( $countries ) : ?>
									<div class="pwdev-overview-item">
										<div class="pwdev-overview-item__icon">
											<svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
											</svg>
										</div>
										<div class="pwdev-overview-item__content">
											<span class="pwdev-overview-item__label">Countries Served</span>
											<span class="pwdev-overview-item__value"><?php echo esc_html( $countries ); ?></span>
										</div>
									</div>
									<?php endif; ?>
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
									<span class="pwdev-separator">&middot;</span>
									<span class="pwdev-date">Updated <?php the_modified_date('M j, Y'); ?></span>
									<span class="pwdev-separator">&middot;</span>
									<span class="pwdev-read-time">8 min read</span>
								</div>
								
								<div class="pwdev-review-content">
									<?php if ( $overview_content ) : ?>
										<h3>Overview</h3>
										<?php echo apply_filters( 'the_content', $overview_content ); ?>
									<?php endif; ?>

									<?php if ( $trading_experience_content ) : ?>
										<h3>Trading Experience</h3>
										<?php echo apply_filters( 'the_content', $trading_experience_content ); ?>
									<?php endif; ?>

									<?php if ( $fees_pricing_content ) : ?>
										<h3>Fees &amp; Pricing</h3>
										<?php echo apply_filters( 'the_content', $fees_pricing_content ); ?>
									<?php endif; ?>

									<?php
									if ( ! $overview_content && ! $trading_experience_content && ! $fees_pricing_content && $editorial_review ) {
										echo apply_filters( 'the_content', $editorial_review );
									}
									?>
								</div>
							</section>

							<section class="pwdev-profile-section">
								<div class="pwdev-profile-section__header">
									<h2 class="pwdev-profile-section__title">Press Releases</h2>
									<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>" class="pwdev-profile-section__link">View All</a>
								</div>
								<div class="pwdev-press-releases">
									<?php
									$press_query = new WP_Query(
										array(
											'post_type'      => 'post',
											'posts_per_page' => 4,
											'post_status'    => 'publish',
											'orderby'        => 'date',
											'order'          => 'DESC',
										)
									);
									?>
									<?php if ( $press_query->have_posts() ) : ?>
										<?php while ( $press_query->have_posts() ) : $press_query->the_post(); ?>
											<article class="pwdev-press-release">
												<?php if ( has_post_thumbnail() ) : ?>
													<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'pwdev-press-release__image' ) ); ?>
												<?php else : ?>
													<img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=80&h=80&fit=crop" alt="" class="pwdev-press-release__image">
												<?php endif; ?>
												<div class="pwdev-press-release__content">
													<h4 class="pwdev-press-release__title"><?php the_title(); ?></h4>
													<div class="pwdev-press-release__meta">
														<span><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></span>
														<span class="pwdev-press-release__tag"><?php echo esc_html( get_the_category() ? get_the_category()[0]->name : 'News' ); ?></span>
													</div>
												</div>
											</article>
										<?php endwhile; ?>
										<?php wp_reset_postdata(); ?>
									<?php endif; ?>
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
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#111827" stroke-width="2">
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
												$class = ( $i <= $normalized_rating ) ? '' : 'empty';
												echo '<span class="pwdev-card__star ' . $class . '">' . ( $class ? '&#9734;' : '&#9733;' ) . '</span>';
											}
											?>
										</div>
									</div>
									<span class="pwdev-reviews-summary__count">Based on <?php echo esc_html( $review_count ); ?> Reviews</span>
								</div>
								<?php if ( function_exists( 'directorist_is_review_enabled' ) && directorist_is_review_enabled() ) : ?>
								<div class="pwdev-directorist-reviews">
									<?php comments_template(); ?>
								</div>
								<?php endif; ?>
							</section>

							<section class="pwdev-profile-section">
								<h2 class="pwdev-profile-section__title">Location</h2>
								<div class="pwdev-location-map">
									<div class="pwdev-location-map__embed">
										<iframe 
											src="https://maps.google.com/maps?q=<?php echo urlencode( $address ? $address : $display_location ); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" 
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
											<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
												<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
											</svg>
											<?php echo esc_html( $display_location ); ?>
										</p>
										<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode( $display_location ); ?>" target="_blank" rel="noopener" class="pwdev-location-details__link">
											Open in Google Maps
											<svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
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
										<?php $faq_index = 0; ?>
										<?php foreach ( $faqs as $faq_item ) :
											$faq_question = isset( $faq_item['quez'] ) ? $faq_item['quez'] : '';
											$faq_answer   = isset( $faq_item['ans'] ) ? $faq_item['ans'] : '';
											if ( empty( $faq_question ) ) continue;
											$faq_index++;
											$faq_answer_id = 'pwdev-faq-answer-' . $listing_id . '-' . $faq_index;
										?>
										<div class="pwdev-faq-item">
											<button class="pwdev-faq-item__question" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_answer_id ); ?>">
												<span><?php echo esc_html( $faq_question ); ?></span>
												<svg class="pwdev-faq-item__icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
													<path d="m6 9 6 6 6-6"/>
												</svg>
											</button>
											<div class="pwdev-faq-item__answer" id="<?php echo esc_attr( $faq_answer_id ); ?>" hidden>
												<p><?php echo esc_html( $faq_answer ); ?></p>
											</div>
										</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</section>
						</div>

						<aside class="pwdev-profile-sidebar">
						<?php
						// Claim Listing: include the DCL plugin template if the plugin is active.
						// The template handles all its own permission/status checks internally.
						if ( defined( 'DCL_TEMPLATES_DIR' ) && file_exists( DCL_TEMPLATES_DIR . 'claim-listing-template.php' ) ) {
							$field_data = array(
								'custom_block_classes' => '',
								'icon'                 => 'uil uil-check-circle',
								'label'                => __( 'Claim Listing', 'directorist-claim-listing' ),
							);
							include DCL_TEMPLATES_DIR . 'claim-listing-template.php';
						}
						?>
							<div class="pwdev-sidebar-card">
								<h3 class="pwdev-sidebar-card__title">Contact Information</h3>
								<ul class="pwdev-contact-list">
									<?php if ( $phone ) : ?>
									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
											<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
										</svg>
										<span><?php echo esc_html( $phone ); ?></span>
									</li>
									<?php endif; ?>

									<?php if ( $email ) : ?>
									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
											<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
										</svg>
										<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
									</li>
									<?php endif; ?>

									<?php if ( $website ) : ?>
									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
											<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
										</svg>
										<a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener"><?php echo esc_html( parse_url($website, PHP_URL_HOST) ); ?></a>
									</li>
									<?php endif; ?>

									<li class="pwdev-contact-list__item">
										<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
											<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
										</svg>
										<span><?php echo esc_html( $display_location ); ?></span>
									</li>
								</ul>
								<?php if ( $website ) : ?>
								<a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener" class="pwdev-btn pwdev-btn--dark pwdev-btn--block">
									<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="2">
										<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
									</svg>
									Visit Website
								</a>
								<?php endif; ?>
							</div>

							<?php if ( $linkedin_url || $twitter_url || $facebook_url || $youtube_url || $instagram_url || $whatsapp_url || $telegram_url || $skype_url ) : ?>
							<div class="pwdev-sidebar-card">
								<h3 class="pwdev-sidebar-card__title">Social & Messengers</h3>
								<?php if ( $linkedin_url || $twitter_url || $facebook_url || $youtube_url || $instagram_url ) : ?>
								<h4 class="pwdev-sidebar-card__subtitle pwdev-sidebar-card__subtitle--first">Social Media</h4>
								<div class="pwdev-social-links">
									<?php if ( $linkedin_url ) : ?>
									<a href="<?php echo esc_url( $linkedin_url ); ?>" class="pwdev-social-link pwdev-social-link--neutral" aria-label="LinkedIn" target="_blank" rel="noopener">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="#111827">
											<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
										</svg>
									</a>
									<?php endif; ?>
									<?php if ( $twitter_url ) : ?>
									<a href="<?php echo esc_url( $twitter_url ); ?>" class="pwdev-social-link pwdev-social-link--neutral" aria-label="Twitter" target="_blank" rel="noopener">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="#111827">
											<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
										</svg>
									</a>
									<?php endif; ?>
									<?php if ( $facebook_url ) : ?>
									<a href="<?php echo esc_url( $facebook_url ); ?>" class="pwdev-social-link pwdev-social-link--neutral" aria-label="Facebook" target="_blank" rel="noopener">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="#111827">
											<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
										</svg>
									</a>
									<?php endif; ?>
									<?php if ( $youtube_url ) : ?>
									<a href="<?php echo esc_url( $youtube_url ); ?>" class="pwdev-social-link pwdev-social-link--neutral" aria-label="YouTube" target="_blank" rel="noopener">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="#111827">
											<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
										</svg>
									</a>
									<?php endif; ?>
									<?php if ( $instagram_url ) : ?>
									<a href="<?php echo esc_url( $instagram_url ); ?>" class="pwdev-social-link pwdev-social-link--neutral" aria-label="Instagram" target="_blank" rel="noopener">
										<svg width="18" height="18" viewBox="0 0 24 24" fill="#111827">
											<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
										</svg>
									</a>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if ( $whatsapp_url || $telegram_url || $skype_url ) : ?>
								<h4 class="pwdev-sidebar-card__subtitle">Messengers</h4>
								<div class="pwdev-messenger-links pwdev-messenger-links--inline">
									<?php if ( $whatsapp_url ) : ?>
									<a href="<?php echo esc_url( $whatsapp_url ); ?>" class="pwdev-messenger-link pwdev-messenger-link--whatsapp" target="_blank" rel="noopener">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="#ffffff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
										WhatsApp
									</a>
									<?php endif; ?>
									<?php if ( $telegram_url ) : ?>
									<a href="<?php echo esc_url( $telegram_url ); ?>" class="pwdev-messenger-link pwdev-messenger-link--telegram" target="_blank" rel="noopener">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="#ffffff"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
										Telegram
									</a>
									<?php endif; ?>
									<?php if ( $skype_url ) : ?>
									<a href="<?php echo esc_url( $skype_url ); ?>" class="pwdev-messenger-link pwdev-messenger-link--skype" target="_blank" rel="noopener">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="#ffffff"><path d="M12.069 18.874c-4.023 0-5.82-1.979-5.82-3.464 0-.765.561-1.296 1.333-1.296 1.723 0 1.273 2.477 4.487 2.477 1.641 0 2.55-.895 2.55-1.811 0-.551-.269-1.16-1.354-1.429l-3.576-.895c-2.88-.724-3.403-2.286-3.403-3.751 0-3.047 2.861-4.191 5.549-4.191 2.471 0 5.393 1.373 5.393 3.199 0 .784-.688 1.24-1.453 1.24-1.469 0-1.198-2.037-4.164-2.037-1.469 0-2.292.664-2.292 1.617s1.153 1.258 2.157 1.487l2.637.587c2.891.649 3.624 2.346 3.624 3.944 0 2.476-1.902 4.324-5.722 4.324m11.084-4.882l-.029.135-.044-.24c.015.045.044.074.059.12.12-.675.181-1.363.181-2.052 0-1.529-.301-3.012-.898-4.42-.569-1.348-1.395-2.562-2.427-3.596-1.049-1.033-2.247-1.856-3.595-2.426C15.015.301 13.531 0 12 0c-.534 0-1.057.036-1.569.109-.481-.073-.985-.109-1.485-.109C7.231 0 5.691.475 4.341 1.328A8.902 8.902 0 00.972 5.391 9.04 9.04 0 000 9.042c0 .533.045 1.057.135 1.569-.04.361-.074.734-.074 1.093 0 1.529.301 3.012.898 4.42.569 1.348 1.395 2.562 2.427 3.596 1.049 1.034 2.247 1.857 3.595 2.427 1.405.598 2.889.898 4.419.898.534 0 1.057-.045 1.569-.135.473.074.988.135 1.485.135 1.713 0 3.253-.465 4.603-1.318a8.903 8.903 0 003.369-4.063 9.04 9.04 0 00.972-4.065c0-.52-.045-1.033-.12-1.545"/></svg>
										Skype
									</a>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
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
									<svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#111827" stroke-width="1.5">
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

		<?php
	endwhile;
endif;

get_footer();
?>
