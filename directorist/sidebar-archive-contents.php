<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */
use wpWax\OneListing\Directorist_Support;

if ( ! defined( 'ABSPATH' ) ) exit;

$is_elementor    = isset( $listings->atts['is_elementor'] ) ? true : false;
$current_page_id = isset( $_REQUEST['current_page_id'] ) ? esc_attr( $_REQUEST['current_page_id'] ) : get_the_ID();

// Results data
$total_results = isset( $listings->query_results->total ) ? (int) $listings->query_results->total : 0;
$per_page      = ! empty( $listings->listings_per_page ) ? (int) $listings->listings_per_page : 0;
$showing       = ( $per_page > 0 && $total_results > $per_page ) ? $per_page : $total_results;
$total_pages   = isset( $listings->query_results->total_pages ) ? (int) $listings->query_results->total_pages : 1;
$current_page  = isset( $listings->query_results->current_page ) ? (int) $listings->query_results->current_page : 1;

// Directory types with counts
$directory_types      = $listings->listing_types;
$current_type         = $listings->current_listing_type;
$directory_type_stats = array();
if ( ! empty( $directory_types ) && is_array( $directory_types ) ) {
	foreach ( $directory_types as $type_id => $type_info ) {
		$type_name = is_array( $type_info ) ? $type_info['name'] : $type_info;
		$term = is_array( $type_info ) && ! empty( $type_info['term'] ) ? $type_info['term'] : get_term( $type_id, ATBDP_TYPE );
		$count = 0;
		if ( $term && ! is_wp_error( $term ) ) {
			$count_query = new WP_Query( array(
				'post_type'      => ATBDP_POST_TYPE,
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'tax_query'      => array( array(
					'taxonomy' => ATBDP_TYPE,
					'field'    => 'term_id',
					'terms'    => $type_id,
				) ),
			) );
			$count = $count_query->found_posts;
			wp_reset_postdata();
		}
		$directory_type_stats[ $type_id ] = array(
			'label' => $type_name,
			'slug'  => ( $term && ! is_wp_error( $term ) ) ? $term->slug : sanitize_title( $type_name ),
			'count' => $count,
		);
	}
}

// Taxonomies for filters
$categories = get_terms( array( 'taxonomy' => ATBDP_CATEGORY, 'hide_empty' => false, 'parent' => 0 ) );
if ( is_wp_error( $categories ) ) $categories = array();

$locations = get_terms( array( 'taxonomy' => ATBDP_LOCATION, 'hide_empty' => false, 'parent' => 0 ) );
if ( is_wp_error( $locations ) ) $locations = array();

$all_tags = get_terms( array( 'taxonomy' => ATBDP_TAGS, 'hide_empty' => false ) );
if ( is_wp_error( $all_tags ) ) $all_tags = array();

// Dynamic filter values from listing meta
$regulation_values       = array();
$trading_platform_values = array();
$spreads_from_values     = array();
$min_deposit_values      = array();
$account_type_values     = array();

$meta_listings = get_posts( array(
	'post_type'      => ATBDP_POST_TYPE,
	'posts_per_page' => -1,
	'fields'         => 'ids',
	'post_status'    => 'publish',
) );

foreach ( $meta_listings as $ml_id ) {
	$reg = get_post_meta( $ml_id, '_regulation', true );
	if ( $reg ) {
		foreach ( array_map( 'trim', explode( ',', $reg ) ) as $r ) {
			if ( $r !== '' ) $regulation_values[ $r ] = isset( $regulation_values[ $r ] ) ? $regulation_values[ $r ] + 1 : 1;
		}
	}
	$tp = get_post_meta( $ml_id, '_trading_platforms', true );
	if ( $tp ) {
		foreach ( array_map( 'trim', explode( ',', $tp ) ) as $t ) {
			if ( $t !== '' ) $trading_platform_values[ $t ] = isset( $trading_platform_values[ $t ] ) ? $trading_platform_values[ $t ] + 1 : 1;
		}
	}
	$sf = get_post_meta( $ml_id, '_spreads_from', true );
	if ( $sf ) {
		$spreads_from_values[ $sf ] = isset( $spreads_from_values[ $sf ] ) ? $spreads_from_values[ $sf ] + 1 : 1;
	}
	$md = get_post_meta( $ml_id, '_min_deposit', true );
	if ( $md !== '' ) {
		$md_num = (float) preg_replace( '/[^0-9.]/', '', (string) $md );
		if ( $md_num >= 0 ) {
			$min_deposit_values[] = $md_num;
		}
	}
	$at = get_post_meta( $ml_id, '_account_type', true );
	if ( $at ) {
		foreach ( array_map( 'trim', explode( ',', $at ) ) as $a ) {
			if ( $a !== '' ) $account_type_values[ $a ] = isset( $account_type_values[ $a ] ) ? $account_type_values[ $a ] + 1 : 1;
		}
	}
}
arsort( $regulation_values );
arsort( $trading_platform_values );
arsort( $spreads_from_values );
arsort( $account_type_values );

$deposit_min = ! empty( $min_deposit_values ) ? (int) min( $min_deposit_values ) : 0;
$deposit_max = ! empty( $min_deposit_values ) ? (int) max( $min_deposit_values ) : 10000;
if ( $deposit_max <= $deposit_min ) {
	$deposit_max = $deposit_min + 10000;
}

// Search & filter URLs
$search_page_id = get_directorist_option( 'search_result_page' );
$search_url     = $search_page_id ? get_permalink( $search_page_id ) : home_url( '/' );

// Current filter values from URL
$current_q    = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
$current_cat  = isset( $_GET['in_cat'] ) ? absint( $_GET['in_cat'] ) : 0;
$current_loc  = isset( $_GET['in_loc'] ) ? absint( $_GET['in_loc'] ) : 0;
$current_tags = isset( $_GET['in_tag'] ) ? array_map( 'sanitize_text_field', (array) $_GET['in_tag'] ) : array();
$current_view = ! empty( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : $listings->view;
$current_rating   = isset( $_GET['search_by_rating'] ) ? (int) $_GET['search_by_rating'] : 0;
$current_dir_slug = isset( $_GET['directory_type'] ) ? sanitize_text_field( wp_unslash( $_GET['directory_type'] ) ) : '';

// Custom meta field filters from URL (e.g. ?custom_field[regulation][]=FCA)
$raw_custom_fields     = isset( $_GET['custom_field'] ) && is_array( $_GET['custom_field'] ) ? wp_unslash( $_GET['custom_field'] ) : array();
$current_regulations   = isset( $raw_custom_fields['regulation'] ) ? array_map( 'sanitize_text_field', (array) $raw_custom_fields['regulation'] ) : array();
$current_platforms     = isset( $raw_custom_fields['trading_platforms'] ) ? array_map( 'sanitize_text_field', (array) $raw_custom_fields['trading_platforms'] ) : array();
$current_spreads       = isset( $raw_custom_fields['spreads_from'] ) ? array_map( 'sanitize_text_field', (array) $raw_custom_fields['spreads_from'] ) : array();
$current_account_types = isset( $raw_custom_fields['account_type'] ) ? array_map( 'sanitize_text_field', (array) $raw_custom_fields['account_type'] ) : array();
$current_deposit_raw   = isset( $raw_custom_fields['min_deposit'] ) ? sanitize_text_field( $raw_custom_fields['min_deposit'] ) : '';

if ( $current_deposit_raw && strpos( $current_deposit_raw, '-' ) !== false ) {
	$_parts = array_map( 'intval', explode( '-', $current_deposit_raw, 2 ) );
	$current_deposit_min = $_parts[0];
	$current_deposit_max = isset( $_parts[1] ) ? $_parts[1] : $deposit_max;
} else {
	$current_deposit_min = $deposit_min;
	$current_deposit_max = $deposit_max;
}

$current_radius       = isset( $_GET['search_radius'] ) ? (int) $_GET['search_radius'] : 50;
$current_verified_only = ! empty( $_GET['verified_only'] ) ? 1 : 0;

// Sort & View data
$sort_by_links = $listings->get_sort_by_link_list();
$view_as_links = $listings->get_view_as_link_list();
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>

<?php if ( $is_elementor ) { ?>
	<div class="col-12"><?php $listings->archive_view_template(); ?></div>
<?php } else { ?>

  <!-- Hero Section -->
  <header class="pwdev-hero">
    <div class="pwdev-container">
      <!-- Trust Badge -->
      <div class="pwdev-hero__badge">
        <svg class="pwdev-hero__badge-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        <span><?php printf( esc_html__( 'Trusted by %s+ traders worldwide', 'onelisting' ), number_format_i18n( $total_results > 0 ? $total_results * 30 : 10000 ) ); ?></span>
      </div>
      
      <!-- Title -->
      <h1 class="pwdev-hero__title">
        <?php
        if ( ! empty( $listings->atts['category'] ) ) {
            $cat_term = get_term_by( 'slug', $listings->atts['category'], ATBDP_CATEGORY );
            if ( $cat_term ) { echo esc_html( $cat_term->name ); }
        } else {
            echo wp_kses_post( Directorist_Support::get_header_title( $current_page_id ) );
        }
        ?>
      </h1>
      <p class="pwdev-hero__subtitle"><?php esc_html_e( 'Compare brokers, technology providers, and services trusted by the trading industry', 'onelisting' ); ?></p>
      
      <!-- Search Bar -->
      <form class="pwdev-search-bar" action="<?php echo esc_url( $search_url ); ?>" method="GET">
        <div class="pwdev-search-bar__input-wrapper">
          <svg class="pwdev-search-bar__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
          <input type="text" name="q" class="pwdev-search-bar__input" placeholder="<?php esc_attr_e( 'Search brokers, providers, services...', 'onelisting' ); ?>" value="<?php echo esc_attr( $current_q ); ?>">
        </div>
        <select name="in_cat" class="pwdev-search-bar__select" aria-label="<?php esc_attr_e( 'Category', 'onelisting' ); ?>">
          <option value=""><?php esc_html_e( 'All Categories', 'onelisting' ); ?></option>
          <?php if ( ! empty( $categories ) ) : foreach ( $categories as $cat ) : ?>
            <option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $current_cat, $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
          <?php endforeach; endif; ?>
        </select>
        <select name="in_loc" class="pwdev-search-bar__select" aria-label="<?php esc_attr_e( 'Location', 'onelisting' ); ?>">
          <option value=""><?php esc_html_e( 'Location', 'onelisting' ); ?></option>
          <?php if ( ! empty( $locations ) ) : foreach ( $locations as $loc ) : ?>
            <option value="<?php echo esc_attr( $loc->term_id ); ?>" <?php selected( $current_loc, $loc->term_id ); ?>><?php echo esc_html( $loc->name ); ?></option>
          <?php endforeach; endif; ?>
        </select>
        <button type="submit" class="pwdev-search-bar__button" aria-label="<?php esc_attr_e( 'Search', 'onelisting' ); ?>">
          <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
        </button>
      </form>
      
      <!-- Stats -->
      <?php if ( ! empty( $directory_type_stats ) ) : ?>
      <div class="pwdev-stats">
        <?php foreach ( $directory_type_stats as $type_id => $type_data ) : 
            $icon_svg = '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>'; // Default chart icon
            if ( strpos( $type_data['slug'], 'tech' ) !== false ) {
                $icon_svg = '<rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>';
            } elseif ( strpos( $type_data['slug'], 'service' ) !== false ) {
                $icon_svg = '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>';
            }
        ?>
        <div class="pwdev-stats__item">
          <svg class="pwdev-stats__icon pwdev-stats__icon--<?php echo esc_attr( $type_data['slug'] ); ?>" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <?php echo $icon_svg; ?>
          </svg>
          <span><?php echo esc_html( $type_data['count'] . '+ ' . $type_data['label'] ); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </header>
  
  <!-- Tabs -->
  <?php if ( ! empty( $directory_type_stats ) && count( $directory_type_stats ) > 1 ) : ?>
  <nav class="pwdev-tabs" aria-label="<?php esc_attr_e( 'Directory categories', 'onelisting' ); ?>">
    <ul class="pwdev-tabs__list">
      <?php foreach ( $directory_type_stats as $type_id => $type_data ) : 
        $is_active = ( (int) $type_id === (int) $current_type );
        $tab_link  = add_query_arg( 'directory_type', $type_data['slug'] );
        
        $icon_svg = '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>';
        if ( strpos( $type_data['slug'], 'tech' ) !== false ) {
            $icon_svg = '<rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>';
        } elseif ( strpos( $type_data['slug'], 'service' ) !== false ) {
            $icon_svg = '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>';
        }
      ?>
      <li class="pwdev-tabs__item<?php echo $is_active ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $type_data['slug'] ); ?>">
        <a href="<?php echo esc_url( $tab_link ); ?>" style="display: flex; align-items: center; color: inherit; text-decoration: none;">
            <svg class="pwdev-tabs__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <?php echo $icon_svg; ?>
            </svg>
            <span><?php echo esc_html( $type_data['label'] ); ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <?php endif; ?>
  
  <!-- Main Content -->
  <main class="pwdev-main-content">
    <!-- Sidebar Filters -->
    <aside class="pwdev-sidebar">
      <div class="pwdev-filters">
        <div class="pwdev-filters__header">
          <h2 class="pwdev-filters__title"><?php esc_html_e( 'Filters', 'onelisting' ); ?></h2>
          <a href="<?php echo esc_url( remove_query_arg( array_keys( $_GET ) ) ); ?>" class="pwdev-filters__clear"><?php esc_html_e( 'Clear All', 'onelisting' ); ?></a>
        </div>
        
        <!-- Directory Filter -->
        <?php if ( ! empty( $directory_type_stats ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title"><?php esc_html_e( 'Directory', 'onelisting' ); ?></h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php foreach ( $directory_type_stats as $type_id => $type_data ) :
              $is_checked = ( (int) $type_id === (int) $current_type );
              $filter_link = add_query_arg( 'directory_type', $type_data['slug'] );
            ?>
            <label class="pwdev-filter-option" onclick="window.location.href='<?php echo esc_url( $filter_link ); ?>'">
              <span class="pwdev-filter-option__checkbox<?php echo $is_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $type_data['label'] ); ?></span>
              <span class="pwdev-filter-option__count"><?php echo esc_html( $type_data['count'] ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Regulation Filter -->
        <?php if ( ! empty( $regulation_values ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Regulation</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php foreach ( $regulation_values as $reg_label => $reg_count ) :
              $is_reg_checked = in_array( $reg_label, $current_regulations );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="regulation"
                   data-filter-value="<?php echo esc_attr( $reg_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_reg_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $reg_label ); ?></span>
              <span class="pwdev-filter-option__count"><?php echo esc_html( $reg_count ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Trading Platforms Filter -->
        <?php if ( ! empty( $trading_platform_values ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Trading Platforms</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php foreach ( $trading_platform_values as $tp_label => $tp_count ) :
              $is_tp_checked = in_array( $tp_label, $current_platforms );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="trading_platforms"
                   data-filter-value="<?php echo esc_attr( $tp_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_tp_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $tp_label ); ?></span>
              <span class="pwdev-filter-option__count"><?php echo esc_html( $tp_count ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Min Deposit Filter (Static Slider) -->
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Min Deposit</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php
            $dep_range = $deposit_max - $deposit_min;
            $dep_min_pct = $dep_range > 0 ? round( ( ( $current_deposit_min - $deposit_min ) / $dep_range ) * 100 ) : 0;
            $dep_max_pct = $dep_range > 0 ? round( ( ( $current_deposit_max - $deposit_min ) / $dep_range ) * 100 ) : 100;
            ?>
            <div class="pwdev-range-slider"
                 data-slider-type="deposit"
                 data-min="<?php echo esc_attr( $deposit_min ); ?>"
                 data-max="<?php echo esc_attr( $deposit_max ); ?>"
                 data-current-min="<?php echo esc_attr( $current_deposit_min ); ?>"
                 data-current-max="<?php echo esc_attr( $current_deposit_max ); ?>"
                 data-prefix="$">
              <div class="pwdev-range-slider__track">
                <div class="pwdev-range-slider__fill" style="left:<?php echo (int)$dep_min_pct; ?>%;width:<?php echo (int)($dep_max_pct - $dep_min_pct); ?>%;"></div>
                <div class="pwdev-range-slider__thumb pwdev-range-slider__thumb--min" style="left:<?php echo (int)$dep_min_pct; ?>;" data-thumb="min"></div>
                <div class="pwdev-range-slider__thumb pwdev-range-slider__thumb--max" style="left:<?php echo (int)$dep_max_pct; ?>%;" data-thumb="max"></div>
              </div>
              <div class="pwdev-range-slider__values">
                <span class="pwdev-range-slider__value--min">$<?php echo number_format( $current_deposit_min ); ?></span>
                <span class="pwdev-range-slider__value--max">$<?php echo number_format( $current_deposit_max ); ?></span>
              </div>
              <input type="hidden" class="pwdev-range-slider__input-min" value="<?php echo esc_attr( $current_deposit_min ); ?>">
              <input type="hidden" class="pwdev-range-slider__input-max" value="<?php echo esc_attr( $current_deposit_max ); ?>">
            </div>
          </div>
        </div>
        
        <!-- Spreads From Filter -->
        <?php if ( ! empty( $spreads_from_values ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Spreads From</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php foreach ( $spreads_from_values as $sf_label => $sf_count ) :
              $is_sf_checked = in_array( $sf_label, $current_spreads );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="spreads_from"
                   data-filter-value="<?php echo esc_attr( $sf_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_sf_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $sf_label ); ?></span>
              <span class="pwdev-filter-option__count"><?php echo esc_html( $sf_count ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Account Type Filter (Static) -->
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Account Type</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php
            $acct_options = ! empty( $account_type_values )
                ? $account_type_values
                : array( 'Standard' => 0, 'ECN / Raw' => 0, 'Islamic' => 0, 'Demo' => 0 );
            foreach ( $acct_options as $at_label => $at_count ) :
              $is_at_checked = in_array( $at_label, $current_account_types );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="account_type"
                   data-filter-value="<?php echo esc_attr( $at_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_at_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $at_label ); ?></span>
              <?php if ( $at_count > 0 ) : ?><span class="pwdev-filter-option__count"><?php echo esc_html( $at_count ); ?></span><?php endif; ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        
        <!-- Minimum Rating Filter -->
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title"><?php esc_html_e( 'Minimum Rating', 'onelisting' ); ?></h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content pwdev-rating-options">
            <?php for ( $r = 5; $r >= 3; $r-- ) : 
                $is_checked = ( $current_rating === $r );
                $rating_link = add_query_arg( 'search_by_rating', $r );
            ?>
            <label class="pwdev-rating-option" onclick="window.location.href='<?php echo esc_url( $rating_link ); ?>'">
              <span class="pwdev-rating-option__radio<?php echo $is_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-rating-option__stars">
                <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                    <span class="pwdev-rating-option__star<?php echo $s > $r ? ' empty' : ''; ?>"><?php echo $s <= $r ? '★' : '☆'; ?></span>
                <?php endfor; ?>
              </span>
              <span class="pwdev-rating-option__label"><?php esc_html_e( '& up', 'onelisting' ); ?></span>
            </label>
            <?php endfor; ?>
          </div>
        </div>
        
        <!-- Location Filter -->
        <?php if ( ! empty( $locations ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title"><?php esc_html_e( 'Location', 'onelisting' ); ?></h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <div class="pwdev-search-location" style="margin-bottom: 0.5rem;">
              <input type="text" placeholder="<?php esc_attr_e( 'Search location...', 'onelisting' ); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; font-size: 0.875rem;">
            </div>
            <?php foreach ( $locations as $loc ) :
                $is_checked = ( $current_loc === (int) $loc->term_id );
                $loc_link = add_query_arg( 'in_loc', $loc->term_id );
            ?>
            <label class="pwdev-filter-option" onclick="window.location.href='<?php echo esc_url( $loc_link ); ?>'">
              <span class="pwdev-filter-option__checkbox<?php echo $is_checked ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $loc->name ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Search Radius (Static) -->
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Search Radius</h3>
            <span style="font-size: 0.75rem; color: #dc2626;">50 mi</span>
          </div>
          <div class="pwdev-filter-section__content">
            <div class="pwdev-range-slider"
                 data-slider-type="radius"
                 data-min="0"
                 data-max="100"
                 data-current-max="<?php echo esc_attr( $current_radius ); ?>"
                 data-suffix=" mi">
              <div class="pwdev-range-slider__track">
                <div class="pwdev-range-slider__fill" style="left:0%;width:<?php echo esc_attr( min(100,$current_radius) ); ?>%;"></div>
                <div class="pwdev-range-slider__thumb pwdev-range-slider__thumb--max" style="left:<?php echo esc_attr( min(100,$current_radius) ); ?>;" data-thumb="max"></div>
              </div>
              <div class="pwdev-range-slider__values">
                <span>0 mi</span>
                <span class="pwdev-range-slider__value--max"><?php echo esc_html( $current_radius ); ?> mi</span>
              </div>
              <input type="hidden" class="pwdev-range-slider__input-max" value="<?php echo esc_attr( $current_radius ); ?>">
            </div>
            <label class="pwdev-filter-option" style="margin-top: 0.5rem;">
              <span class="pwdev-filter-option__checkbox"></span>
              <span class="pwdev-filter-option__label">Use My Location</span>
            </label>
          </div>
        </div>
        
        <!-- Verified Only Toggle -->
        <div class="pwdev-toggle-wrapper">
          <span class="pwdev-toggle-label">Verified Only</span>
          <div class="pwdev-toggle<?php echo $current_verified_only ? ' active' : ''; ?>" data-filter-type="verifiedOnly">
            <div class="pwdev-toggle__knob"></div>
          </div>
        </div>
        
        <!-- Apply Filters Button -->
        <button class="pwdev-filters__apply">Apply Filters</button>
      </div>
    </aside>
    
    <!-- Results Area -->
    <section class="pwdev-results">
      <div class="pwdev-results__header">
        <p class="pwdev-results__count"><?php printf( wp_kses_post( __( 'Showing <strong>%1$s</strong> of <strong>%2$s</strong> Results', 'onelisting' ) ), $showing, $total_results ); ?></p>
        <div class="pwdev-results__controls">
          <!-- Mobile Filters Button -->
          <button class="pwdev-results__filters-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span><?php esc_html_e( 'Filters', 'onelisting' ); ?></span>
          </button>
          
          <!-- View Toggle -->
          <div class="pwdev-results__view-toggle">
            <button class="pwdev-results__view-btn active" data-view="grid" aria-label="Grid view">
              <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
              </svg>
            </button>
            <button class="pwdev-results__view-btn" data-view="list" aria-label="List view">
              <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/>
              </svg>
            </button>
          </div>
          
          <!-- Sort -->
          <div class="pwdev-results__sort">
            <span class="pwdev-results__sort-label"><?php esc_html_e( 'Sort:', 'onelisting' ); ?></span>
            <select class="pwdev-results__sort-select" onchange="window.location.href=this.value">
              <?php if ( ! empty( $sort_by_links ) ) : ?>
                <?php foreach ( $sort_by_links as $slink ) : ?>
                <option value="<?php echo esc_url( $slink['link'] ); ?>" <?php if ( isset( $slink['key'], $_GET['sort'] ) ) selected( sanitize_text_field( wp_unslash( $_GET['sort'] ) ), $slink['key'] ); ?>><?php echo esc_html( $slink['label'] ); ?></option>
                <?php endforeach; ?>
              <?php else : ?>
                <option value="">Rating (High-Low)</option>
                <option value="">Rating (Low-High)</option>
                <option value="">Name (A-Z)</option>
                <option value="">Name (Z-A)</option>
                <option value="">Most Reviews</option>
              <?php endif; ?>
            </select>
          </div>
        </div>
      </div>
      
      <!-- Cards Grid -->
      <div class="pwdev-cards-grid">
        <?php
        if ( $listings->have_posts() ) :
            $original_post = $GLOBALS['post'];
            
            // Setting up WP loop variables using Directorist listings
            foreach ( $listings->post_ids() as $listing_id ) :
                $GLOBALS['post'] = get_post( $listing_id );
                setup_postdata( $GLOBALS['post'] );

                $id        = get_the_ID();
                $cats      = get_the_terms( $id, ATBDP_CATEGORY );
                $locs      = get_the_terms( $id, ATBDP_LOCATION );
                $tags      = get_the_terms( $id, ATBDP_TAGS );
                $featured  = get_post_meta( $id, '_featured', true );
                
                // Custom fields based on user input design (Min Deposit, Spreads)
                $min_deposit = get_post_meta( $id, '_min_deposit', true );
                $spreads     = get_post_meta( $id, '_spreads_from', true );

                // Preview image
                $prv_img_id = directorist_get_listing_preview_image( $id );
                $thumb_url  = $prv_img_id ? wp_get_attachment_image_url( $prv_img_id, 'medium' ) : '';
                if ( ! $thumb_url ) {
                    $gallery = directorist_get_listing_gallery_images( $id );
                    if ( ! empty( $gallery ) ) {
                        $thumb_url = wp_get_attachment_image_url( reset( $gallery ), 'medium' );
                    }
                }

                // Logo initials fallback
                $title_text = get_the_title();
                $words      = preg_split( '/\s+/', $title_text );
                $initials   = '';
                if ( count( $words ) >= 2 ) {
                    $initials = mb_strtoupper( mb_substr( $words[0], 0, 1 ) ) . mb_strtoupper( mb_substr( $words[1], 0, 1 ) );
                } else {
                    $initials = mb_strtoupper( mb_substr( $title_text, 0, 2 ) );
                }

                // Rating
                $average_rating = directorist_get_listing_rating( $id );
                $review_count   = directorist_get_listing_review_count( $id );

                // Category (first)
                $cat_name = '';
                if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                    $cat_name = $cats[0]->name;
                }

                // Location (first)
                $loc_name = '';
                if ( ! empty( $locs ) && ! is_wp_error( $locs ) ) {
                    $loc_name = $locs[0]->name;
                }
                
                // Favourite
                $is_favourite = false;
                if ( is_user_logged_in() ) {
                    $favourites   = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );
                    $is_favourite = in_array( $id, $favourites );
                }
        ?>
        <article class="pwdev-card<?php echo $featured ? ' pwdev-card--featured' : ''; ?>">
          <div class="pwdev-card__header">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array( 'class' => 'pwdev-card__logo-img', 'style' => 'width:48px;height:48px;border-radius:8px;object-fit:cover;' ) ); ?>
            <?php elseif ( $thumb_url ) : ?>
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" class="pwdev-card__logo-img" style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
            <?php else : ?>
                <div class="pwdev-card__logo"><?php echo esc_html( $initials ); ?></div>
            <?php endif; ?>
            
            <button class="pwdev-card__bookmark<?php echo $is_favourite ? ' atbd_active' : ''; ?>" aria-label="<?php esc_attr_e( 'Bookmark', 'onelisting' ); ?>" data-listing-id="<?php echo esc_attr( $id ); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" fill="<?php echo $is_favourite ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
              </svg>
            </button>
          </div>
          <h3 class="pwdev-card__name"><a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a></h3>
          
          <?php if ( has_excerpt() ) : ?>
          <div class="pwdev-card__excerpt" style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
             <?php the_excerpt(); ?>
          </div>
          <?php endif; ?>
          
          <div class="pwdev-card__rating">
            <div class="pwdev-card__stars">
              <?php for ( $i = 1; $i <= 5; $i++ ) :
                if ( $average_rating >= $i ) : ?>
                  <span class="pwdev-card__star">★</span>
                <?php elseif ( $average_rating >= $i - 0.5 ) : ?>
                  <span class="pwdev-card__star">★</span>
                <?php else : ?>
                  <span class="pwdev-card__star empty">☆</span>
                <?php endif;
              endfor; ?>
            </div>
            <span class="pwdev-card__reviews">(<?php echo esc_html( $review_count ); ?> <?php echo esc_html( _n( 'review', 'reviews', $review_count, 'onelisting' ) ); ?>)</span>
          </div>
          <div class="pwdev-card__meta">
            <?php if ( $cat_name ) : ?>
            <span class="pwdev-card__type"><?php echo esc_html( $cat_name ); ?></span>
            <?php endif; ?>
            <?php if ( $cat_name && $loc_name ) : ?>
            <span class="pwdev-card__meta-separator">·</span>
            <?php endif; ?>
            <?php if ( $loc_name ) : ?>
            <span class="pwdev-card__location">
              <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
              </svg>
              <?php echo esc_html( $loc_name ); ?>
            </span>
            <?php endif; ?>
          </div>
          
          <?php if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
          <div class="pwdev-card__badges">
            <?php foreach ( $tags as $tag ) : ?>
            <span class="pwdev-badge pwdev-badge--<?php echo esc_attr( $tag->slug ); ?>"><?php echo esc_html( $tag->name ); ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          
          <?php if ( $min_deposit || $spreads ) : ?>
          <div class="pwdev-card__details">
            <?php if ( $min_deposit ) : ?>
            <div class="pwdev-card__detail">
              <span class="pwdev-card__detail-label">Min Deposit</span>
              <span class="pwdev-card__detail-value"><?php echo esc_html( $min_deposit ); ?></span>
            </div>
            <?php endif; ?>
            <?php if ( $spreads ) : ?>
            <div class="pwdev-card__detail">
              <span class="pwdev-card__detail-label">Spreads</span>
              <span class="pwdev-card__detail-value"><?php echo esc_html( $spreads ); ?></span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          
          <a href="<?php the_permalink(); ?>" class="pwdev-card__action">View Profile</a>
        </article>
        <?php 
            endforeach;
            $GLOBALS['post'] = $original_post;
            wp_reset_postdata();
        else : 
        ?>
            <div class="pwdev-card" style="grid-column: 1/-1; padding: 2rem; text-align: center;">
                <p><?php esc_html_e( 'No listings found.', 'onelisting' ); ?></p>
            </div>
        <?php endif; ?>
      </div>
      
      <!-- Pagination -->
      <?php if ( $total_pages > 1 ) : ?>
      <nav class="pwdev-pagination" aria-label="<?php esc_attr_e( 'Results pagination', 'onelisting' ); ?>">
        <?php 
        $prev_link = ( $current_page > 1 ) ? add_query_arg( 'paged', $current_page - 1 ) : '';
        $next_link = ( $current_page < $total_pages ) ? add_query_arg( 'paged', $current_page + 1 ) : '';
        ?>
        
        <?php if ( $prev_link ) : ?>
        <a href="<?php echo esc_url( $prev_link ); ?>" class="pwdev-pagination__btn" aria-label="<?php esc_attr_e( 'Previous page', 'onelisting' ); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m15 18-6-6 6-6"/>
          </svg>
        </a>
        <?php else : ?>
        <button class="pwdev-pagination__btn" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m15 18-6-6 6-6"/>
          </svg>
        </button>
        <?php endif; ?>

        <?php
        $range = 2;
        for ( $p = 1; $p <= $total_pages; $p++ ) :
            if ( $p == 1 || $p == $total_pages || ( $p >= $current_page - $range && $p <= $current_page + $range ) ) :
                if ( $p == $current_page ) : ?>
                    <button class="pwdev-pagination__btn active"><?php echo esc_html( $p ); ?></button>
                <?php else : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'paged', $p ) ); ?>" class="pwdev-pagination__btn"><?php echo esc_html( $p ); ?></a>
                <?php endif;
            elseif ( $p == $current_page - $range - 1 || $p == $current_page + $range + 1 ) : ?>
                <span class="pwdev-pagination__ellipsis">...</span>
            <?php endif;
        endfor;
        ?>

        <?php if ( $next_link ) : ?>
        <a href="<?php echo esc_url( $next_link ); ?>" class="pwdev-pagination__btn" aria-label="<?php esc_attr_e( 'Next page', 'onelisting' ); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m9 18 6-6-6-6"/>
          </svg>
        </a>
        <?php else : ?>
        <button class="pwdev-pagination__btn" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m9 18 6-6-6-6"/>
          </svg>
        </button>
        <?php endif; ?>
      </nav>
      <?php endif; ?>
    </section>
  </main>
  
  <!-- Mobile Filter Modal (Static Copy for Mobile) -->
  <div class="pwdev-filter-modal" id="pwdev-filter-modal">
    <div class="pwdev-filter-modal__content">
      <div class="pwdev-filter-modal__header">
        <h2 class="pwdev-filter-modal__title">Filters</h2>
        <button class="pwdev-filter-modal__close" aria-label="Close filters">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="pwdev-filter-modal__body">
        <!-- Re-use same structure as sidebar filters for mobile modal content if JS copies it, or duplicate here -->
        <!-- For brevity, user provided HTML structure suggests hardcoded duplication or JS handling. I will leave the structure provided. -->
        <?php if ( ! empty( $regulation_values ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Regulation</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php foreach ( $regulation_values as $reg_label => $reg_count ) :
              $is_modal_reg = in_array( $reg_label, $current_regulations );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="regulation"
                   data-filter-value="<?php echo esc_attr( $reg_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_modal_reg ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $reg_label ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php if ( ! empty( $trading_platform_values ) ) : ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Trading Platforms</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php foreach ( $trading_platform_values as $tp_label => $tp_count ) :
              $is_modal_tp = in_array( $tp_label, $current_platforms );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="trading_platforms"
                   data-filter-value="<?php echo esc_attr( $tp_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_modal_tp ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $tp_label ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <div class="pwdev-filter-section">
          <div class="pwdev-filter-section__header">
            <h3 class="pwdev-filter-section__title">Account Type</h3>
            <svg class="pwdev-filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="pwdev-filter-section__content">
            <?php
            $modal_acct = ! empty( $account_type_values ) ? $account_type_values : array( 'Standard' => 0, 'ECN / Raw' => 0, 'Islamic' => 0, 'Demo' => 0 );
            foreach ( $modal_acct as $mat_label => $mat_count ) :
              $is_modal_at = in_array( $mat_label, $current_account_types );
            ?>
            <label class="pwdev-filter-option"
                   data-filter-type="account_type"
                   data-filter-value="<?php echo esc_attr( $mat_label ); ?>">
              <span class="pwdev-filter-option__checkbox<?php echo $is_modal_at ? ' checked' : ''; ?>"></span>
              <span class="pwdev-filter-option__label"><?php echo esc_html( $mat_label ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="pwdev-filter-modal__footer">
        <button class="pwdev-filters__apply">Apply Filters</button>
      </div>
    </div>
  </div>

<?php } ?>

</div>
