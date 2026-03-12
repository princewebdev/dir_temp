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

// Sort & View data
$sort_by_links = $listings->get_sort_by_link_list();
$view_as_links = $listings->get_view_as_link_list();
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>

<?php if ( $is_elementor ) { ?>
	<div class="col-12"><?php $listings->archive_view_template(); ?></div>
<?php } else { ?>

  <!-- Hero Section -->
  <header class="hero">
    <div class="container">
      <!-- Trust Badge -->
      <div class="hero__badge">
        <svg class="hero__badge-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        <span><?php printf( esc_html__( 'Trusted by %s+ traders worldwide', 'onelisting' ), number_format_i18n( $total_results > 0 ? $total_results * 30 : 10000 ) ); ?></span>
      </div>

      <!-- Title -->
      <h1 class="hero__title">
        <?php
        if ( ! empty( $listings->atts['category'] ) ) {
            $cat_term = get_term_by( 'slug', $listings->atts['category'], ATBDP_CATEGORY );
            if ( $cat_term ) { echo esc_html( $cat_term->name ); }
        } else {
            echo wp_kses_post( Directorist_Support::get_header_title( $current_page_id ) );
        }
        ?>
      </h1>
      <p class="hero__subtitle"><?php esc_html_e( 'Compare brokers, technology providers, and services trusted by the trading industry', 'onelisting' ); ?></p>

      <!-- Search Bar -->
      <form class="search-bar" action="<?php echo esc_url( $search_url ); ?>" method="GET">
        <div class="search-bar__input-wrapper">
          <svg class="search-bar__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
          <input type="text" name="q" class="search-bar__input" placeholder="<?php esc_attr_e( 'Search brokers, providers, services...', 'onelisting' ); ?>" value="<?php echo esc_attr( $current_q ); ?>">
        </div>
        <select name="in_cat" class="search-bar__select" aria-label="<?php esc_attr_e( 'Category', 'onelisting' ); ?>">
          <option value=""><?php esc_html_e( 'All Categories', 'onelisting' ); ?></option>
          <?php if ( ! empty( $categories ) ) : foreach ( $categories as $cat ) : ?>
            <option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $current_cat, $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
          <?php endforeach; endif; ?>
        </select>
        <select name="in_loc" class="search-bar__select" aria-label="<?php esc_attr_e( 'Location', 'onelisting' ); ?>">
          <option value=""><?php esc_html_e( 'Location', 'onelisting' ); ?></option>
          <?php if ( ! empty( $locations ) ) : foreach ( $locations as $loc ) : ?>
            <option value="<?php echo esc_attr( $loc->term_id ); ?>" <?php selected( $current_loc, $loc->term_id ); ?>><?php echo esc_html( $loc->name ); ?></option>
          <?php endforeach; endif; ?>
        </select>
        <button type="submit" class="search-bar__button" aria-label="<?php esc_attr_e( 'Search', 'onelisting' ); ?>">
          <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
        </button>
      </form>

      <!-- Stats -->
      <?php if ( ! empty( $directory_type_stats ) ) : ?>
      <div class="stats">
        <?php foreach ( $directory_type_stats as $type_id => $type_data ) : ?>
          <div class="stats__item">
            <svg class="stats__icon stats__icon--<?php echo esc_attr( $type_data['slug'] ); ?>" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
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
  <nav class="tabs" aria-label="<?php esc_attr_e( 'Directory categories', 'onelisting' ); ?>">
    <ul class="tabs__list">
      <?php
      $tab_icons = array(
        '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>',
        '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
        '<rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>',
      );
      $t = 0;
      foreach ( $directory_type_stats as $type_id => $type_data ) :
        $is_active = ( (int) $type_id === (int) $current_type );
        $tab_link  = add_query_arg( 'directory_type', $type_data['slug'] );
        $icon_idx  = min( $t, 2 );
      ?>
      <li class="tabs__item<?php echo $is_active ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $type_data['slug'] ); ?>" data-href="<?php echo esc_url( $tab_link ); ?>" role="tab" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>">
        <svg class="tabs__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <?php echo $tab_icons[ $icon_idx ]; ?>
        </svg>
        <span><?php echo esc_html( $type_data['label'] ); ?></span>
      </li>
      <?php $t++; endforeach; ?>
    </ul>
  </nav>
  <?php endif; ?>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Sidebar Filters -->
    <aside class="sidebar">
      <div class="filters">
        <div class="filters__header">
          <h2 class="filters__title"><?php esc_html_e( 'Filters', 'onelisting' ); ?></h2>
          <button class="filters__clear"><?php esc_html_e( 'Clear All', 'onelisting' ); ?></button>
        </div>

        <!-- Directory Filter -->
        <?php if ( ! empty( $directory_type_stats ) ) : ?>
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title"><?php esc_html_e( 'Directory', 'onelisting' ); ?></h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <?php foreach ( $directory_type_stats as $type_id => $type_data ) :
              $is_checked = ( (int) $type_id === (int) $current_type );
            ?>
              <label class="filter-option" data-filter-type="directory" data-filter-value="<?php echo esc_attr( $type_data['slug'] ); ?>">
                <span class="filter-option__checkbox<?php echo $is_checked ? ' checked' : ''; ?>"></span>
                <span class="filter-option__label"><?php echo esc_html( $type_data['label'] ); ?></span>
                <span class="filter-option__count"><?php echo esc_html( $type_data['count'] ); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Regulation Filter -->
        <!-- TODO: Make dynamic when custom field/taxonomy for regulation is configured -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Regulation</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <label class="filter-option" data-filter-type="regulation" data-filter-value="FCA">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">FCA</span>
            </label>
            <label class="filter-option" data-filter-type="regulation" data-filter-value="CySEC">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">CySEC</span>
            </label>
            <label class="filter-option" data-filter-type="regulation" data-filter-value="ASIC">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">ASIC</span>
            </label>
            <label class="filter-option" data-filter-type="regulation" data-filter-value="BaFin">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">BaFin</span>
            </label>
            <label class="filter-option" data-filter-type="regulation" data-filter-value="FSCA">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">FSCA</span>
            </label>
          </div>
        </div>

        <!-- Trading Platforms Filter -->
        <!-- TODO: Make dynamic when custom field/taxonomy for platforms is configured -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Trading Platforms</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <label class="filter-option" data-filter-type="platforms" data-filter-value="MT4">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">MT4</span>
            </label>
            <label class="filter-option" data-filter-type="platforms" data-filter-value="MT5">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">MT5</span>
            </label>
            <label class="filter-option" data-filter-type="platforms" data-filter-value="cTrader">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">cTrader</span>
            </label>
            <label class="filter-option" data-filter-type="platforms" data-filter-value="TradingView">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">TradingView</span>
            </label>
          </div>
        </div>

        <!-- Min Deposit Filter -->
        <!-- TODO: Make dynamic when custom field for min deposit is configured -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Min Deposit</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <div class="range-slider">
              <div class="range-slider__track">
                <div class="range-slider__fill" style="left: 0%; width: 100%;"></div>
                <div class="range-slider__thumb" style="left: 0%;"></div>
                <div class="range-slider__thumb" style="left: 100%;"></div>
              </div>
              <div class="range-slider__values">
                <span>$0</span>
                <span>$10,000</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Spreads From Filter -->
        <!-- TODO: Make dynamic when custom field for spreads is configured -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Spreads From</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <label class="filter-option" data-filter-type="spreads" data-filter-value="0.0">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">0.0 pips (Raw)</span>
            </label>
            <label class="filter-option" data-filter-type="spreads" data-filter-value="0.4">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">0.4 pips</span>
            </label>
            <label class="filter-option" data-filter-type="spreads" data-filter-value="0.8">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">0.8 pips</span>
            </label>
            <label class="filter-option" data-filter-type="spreads" data-filter-value="1.0">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">1.0 pips</span>
            </label>
            <label class="filter-option" data-filter-type="spreads" data-filter-value="1.5+">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">1.5+ pips</span>
            </label>
          </div>
        </div>

        <!-- Account Type Filter -->
        <!-- TODO: Make dynamic when custom field for account type is configured -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Account Type</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <label class="filter-option" data-filter-type="accountType" data-filter-value="standard">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">Standard</span>
            </label>
            <label class="filter-option" data-filter-type="accountType" data-filter-value="ecn">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">ECN / Raw</span>
            </label>
            <label class="filter-option" data-filter-type="accountType" data-filter-value="islamic">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">Islamic</span>
            </label>
            <label class="filter-option" data-filter-type="accountType" data-filter-value="demo">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">Demo</span>
            </label>
          </div>
        </div>

        <!-- Minimum Rating Filter -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title"><?php esc_html_e( 'Minimum Rating', 'onelisting' ); ?></h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content rating-options">
            <?php
            for ( $r = 5; $r >= 3; $r-- ) :
              $is_checked = ( $current_rating === $r );
            ?>
              <label class="rating-option" data-rating="<?php echo esc_attr( $r ); ?>">
                <span class="rating-option__radio<?php echo $is_checked ? ' checked' : ''; ?>"></span>
                <span class="rating-option__stars">
                  <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                    <span class="rating-option__star<?php echo $s > $r ? ' empty' : ''; ?>"><?php echo $s <= $r ? '★' : '☆'; ?></span>
                  <?php endfor; ?>
                </span>
                <span class="rating-option__label"><?php esc_html_e( '& up', 'onelisting' ); ?></span>
              </label>
            <?php endfor; ?>
          </div>
        </div>

        <!-- Location Filter -->
        <?php if ( ! empty( $locations ) ) : ?>
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title"><?php esc_html_e( 'Location', 'onelisting' ); ?></h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <div class="search-location" style="margin-bottom: 0.5rem;">
              <input type="text" placeholder="<?php esc_attr_e( 'Search location...', 'onelisting' ); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; font-size: 0.875rem;">
            </div>
            <?php foreach ( $locations as $loc ) :
              $is_checked = ( $current_loc === (int) $loc->term_id );
            ?>
              <label class="filter-option" data-filter-type="location" data-filter-value="<?php echo esc_attr( $loc->slug ); ?>">
                <span class="filter-option__checkbox<?php echo $is_checked ? ' checked' : ''; ?>"></span>
                <span class="filter-option__label"><?php echo esc_html( $loc->name ); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Search Radius -->
        <!-- TODO: Make dynamic when location/radius search is configured -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Search Radius</h3>
            <span style="font-size: 0.75rem; color: #dc2626;">50 mi</span>
          </div>
          <div class="filter-section__content">
            <div class="range-slider">
              <div class="range-slider__track">
                <div class="range-slider__fill" style="left: 0%; width: 50%;"></div>
                <div class="range-slider__thumb" style="left: 50%;"></div>
              </div>
              <div class="range-slider__values">
                <span>0 mi</span>
                <span>100 mi</span>
              </div>
            </div>
            <label class="filter-option" style="margin-top: 0.5rem;">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">Use My Location</span>
            </label>
          </div>
        </div>

        <!-- Verified Only Toggle -->
        <!-- TODO: Make dynamic when verified/claimed listing field is configured -->
        <div class="toggle-wrapper">
          <span class="toggle-label"><?php esc_html_e( 'Verified Only', 'onelisting' ); ?></span>
          <div class="toggle active" data-filter-type="verifiedOnly">
            <div class="toggle__knob"></div>
          </div>
        </div>

        <!-- Apply Filters Button -->
        <button class="filters__apply"><?php esc_html_e( 'Apply Filters', 'onelisting' ); ?></button>
      </div>
    </aside>

    <!-- Results Area -->
    <section class="results">
      <div class="results__header">
        <p class="results__count"><?php printf( wp_kses_post( __( 'Showing <strong>%1$s</strong> of <strong>%2$s</strong> Results', 'onelisting' ) ), $showing, $total_results ); ?></p>
        <div class="results__controls">
          <!-- Mobile Filters Button -->
          <button class="results__filters-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span><?php esc_html_e( 'Filters', 'onelisting' ); ?></span>
          </button>

          <!-- View Toggle -->
          <div class="results__view-toggle">
            <?php if ( ! empty( $view_as_links ) ) : ?>
              <?php foreach ( $view_as_links as $vlink ) : ?>
              <button class="results__view-btn<?php echo ! empty( $vlink['active_class'] ) ? ' active' : ''; ?>" data-view="<?php echo esc_attr( strtolower( $vlink['label'] ) ); ?>" data-href="<?php echo esc_url( $vlink['link'] ); ?>" aria-label="<?php echo esc_attr( $vlink['label'] ); ?> view">
                <?php if ( strtolower( $vlink['label'] ) === 'grid' ) : ?>
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                </svg>
                <?php else : ?>
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/>
                </svg>
                <?php endif; ?>
              </button>
              <?php endforeach; ?>
            <?php else : ?>
              <button class="results__view-btn active" data-view="grid" aria-label="Grid view">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                </svg>
              </button>
              <button class="results__view-btn" data-view="list" aria-label="List view">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/>
                </svg>
              </button>
            <?php endif; ?>
          </div>

          <!-- Sort -->
          <div class="results__sort">
            <span class="results__sort-label"><?php esc_html_e( 'Sort:', 'onelisting' ); ?></span>
            <select class="results__sort-select">
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
      <div class="cards-grid">
        <?php
        if ( $listings->have_posts() ) :
          $original_post = $GLOBALS['post'];

          foreach ( $listings->post_ids() as $listing_id ) :
            $GLOBALS['post'] = get_post( $listing_id );
            setup_postdata( $GLOBALS['post'] );

            $id        = $listing_id;
            $title     = get_the_title( $id );
            $permalink = get_permalink( $id );
            $cats      = get_the_terms( $id, ATBDP_CATEGORY );
            $locs      = get_the_terms( $id, ATBDP_LOCATION );
            $tags      = get_the_terms( $id, ATBDP_TAGS );
            $featured  = get_post_meta( $id, '_featured', true );
            $tagline   = get_post_meta( $id, '_tagline', true );

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
            $words    = preg_split( '/\s+/', $title );
            $initials = '';
            if ( count( $words ) >= 2 ) {
              $initials = mb_strtoupper( mb_substr( $words[0], 0, 1 ) ) . mb_strtoupper( mb_substr( $words[1], 0, 1 ) );
            } else {
              $initials = mb_strtoupper( mb_substr( $title, 0, 2 ) );
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

            // Price / custom field details
            $price         = get_post_meta( $id, '_price', true );
            $price_range   = get_post_meta( $id, '_price_range', true );
            $pricing_type  = get_post_meta( $id, '_atbd_listing_pricing', true );

            // Favourite
            $is_favourite = false;
            if ( is_user_logged_in() ) {
              $favourites   = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );
              $is_favourite = in_array( $id, $favourites );
            }
        ?>
        <article class="card<?php echo $featured ? ' card--featured' : ''; ?>">
          <div class="card__header">
            <?php if ( $thumb_url ) : ?>
              <img class="card__logo" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
            <?php else : ?>
              <div class="card__logo"><?php echo esc_html( $initials ); ?></div>
            <?php endif; ?>
            <button class="card__bookmark<?php echo $is_favourite ? ' bookmarked' : ''; ?>" aria-label="<?php esc_attr_e( 'Bookmark', 'onelisting' ); ?>" data-listing-id="<?php echo esc_attr( $id ); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" fill="<?php echo $is_favourite ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
              </svg>
            </button>
          </div>
          <h3 class="card__name"><?php echo esc_html( $title ); ?></h3>
          <div class="card__rating">
            <div class="card__stars">
              <?php for ( $i = 1; $i <= 5; $i++ ) :
                if ( $average_rating >= $i ) : ?>
                  <span class="card__star">★</span>
                <?php elseif ( $average_rating >= $i - 0.5 ) : ?>
                  <span class="card__star half">★</span>
                <?php else : ?>
                  <span class="card__star empty">☆</span>
                <?php endif;
              endfor; ?>
            </div>
            <span class="card__reviews">(<?php echo esc_html( $review_count ); ?> <?php echo esc_html( _n( 'review', 'reviews', $review_count, 'onelisting' ) ); ?>)</span>
          </div>
          <div class="card__meta">
            <?php if ( $cat_name ) : ?>
              <span class="card__type"><?php echo esc_html( $cat_name ); ?></span>
            <?php endif; ?>
            <?php if ( $cat_name && $loc_name ) : ?>
              <span class="card__meta-separator">·</span>
            <?php endif; ?>
            <?php if ( $loc_name ) : ?>
              <span class="card__location">
                <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                <?php echo esc_html( $loc_name ); ?>
              </span>
            <?php endif; ?>
          </div>
          <?php if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
          <div class="card__badges">
            <?php foreach ( $tags as $tag ) : ?>
              <span class="badge badge--<?php echo esc_attr( $tag->slug ); ?>"><?php echo esc_html( $tag->name ); ?></span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <?php if ( $price || $tagline ) : ?>
          <div class="card__details">
            <?php if ( $price ) : ?>
              <div class="card__detail">
                <span class="card__detail-label"><?php esc_html_e( 'Price', 'onelisting' ); ?></span>
                <span class="card__detail-value"><?php echo esc_html( $listings->c_symbol . $price ); ?></span>
              </div>
            <?php endif; ?>
            <?php if ( $tagline ) : ?>
              <div class="card__detail">
                <span class="card__detail-label"><?php esc_html_e( 'Info', 'onelisting' ); ?></span>
                <span class="card__detail-value"><?php echo esc_html( $tagline ); ?></span>
              </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <button class="card__action" data-broker-id="<?php echo esc_attr( $id ); ?>" data-href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'View Profile', 'onelisting' ); ?></button>
        </article>
        <?php
          endforeach;

          $GLOBALS['post'] = $original_post;
          wp_reset_postdata();

        else : ?>
          <div class="cards-grid__empty">
            <p><?php esc_html_e( 'No listings found.', 'onelisting' ); ?></p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ( $total_pages > 1 ) : ?>
      <nav class="pagination" aria-label="<?php esc_attr_e( 'Results pagination', 'onelisting' ); ?>">
        <?php if ( $current_page > 1 ) : ?>
          <a href="<?php echo esc_url( add_query_arg( 'paged', $current_page - 1 ) ); ?>" class="pagination__btn" aria-label="<?php esc_attr_e( 'Previous page', 'onelisting' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m15 18-6-6 6-6"/>
            </svg>
          </a>
        <?php else : ?>
          <button class="pagination__btn" aria-label="<?php esc_attr_e( 'Previous page', 'onelisting' ); ?>" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m15 18-6-6 6-6"/>
            </svg>
          </button>
        <?php endif; ?>

        <?php
        // Page number links
        $range = 2;
        for ( $p = 1; $p <= $total_pages; $p++ ) :
          if ( $p === 1 || $p === $total_pages || ( $p >= $current_page - $range && $p <= $current_page + $range ) ) :
            if ( $p === $current_page ) : ?>
              <button class="pagination__btn active"><?php echo esc_html( $p ); ?></button>
            <?php else : ?>
              <a href="<?php echo esc_url( add_query_arg( 'paged', $p ) ); ?>" class="pagination__btn"><?php echo esc_html( $p ); ?></a>
            <?php endif;
          elseif ( $p === $current_page - $range - 1 || $p === $current_page + $range + 1 ) : ?>
            <span class="pagination__ellipsis">...</span>
          <?php endif;
        endfor;
        ?>

        <?php if ( $current_page < $total_pages ) : ?>
          <a href="<?php echo esc_url( add_query_arg( 'paged', $current_page + 1 ) ); ?>" class="pagination__btn" aria-label="<?php esc_attr_e( 'Next page', 'onelisting' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m9 18 6-6-6-6"/>
            </svg>
          </a>
        <?php else : ?>
          <button class="pagination__btn" aria-label="<?php esc_attr_e( 'Next page', 'onelisting' ); ?>" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m9 18 6-6-6-6"/>
            </svg>
          </button>
        <?php endif; ?>
      </nav>
      <?php endif; ?>

    </section>
  </main>

  <!-- Mobile Filter Modal -->
  <div class="filter-modal" id="filter-modal">
    <div class="filter-modal__content">
      <div class="filter-modal__header">
        <h2 class="filter-modal__title"><?php esc_html_e( 'Filters', 'onelisting' ); ?></h2>
        <button class="filter-modal__close" aria-label="<?php esc_attr_e( 'Close filters', 'onelisting' ); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="filter-modal__body">
        <!-- Regulation -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Regulation</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <label class="filter-option" data-filter-type="regulation" data-filter-value="FCA">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">FCA</span>
            </label>
            <label class="filter-option" data-filter-type="regulation" data-filter-value="CySEC">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">CySEC</span>
            </label>
            <label class="filter-option" data-filter-type="regulation" data-filter-value="ASIC">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">ASIC</span>
            </label>
          </div>
        </div>

        <!-- Trading Platforms -->
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title">Trading Platforms</h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <label class="filter-option" data-filter-type="platforms" data-filter-value="MT4">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">MT4</span>
            </label>
            <label class="filter-option" data-filter-type="platforms" data-filter-value="MT5">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">MT5</span>
            </label>
            <label class="filter-option" data-filter-type="platforms" data-filter-value="cTrader">
              <span class="filter-option__checkbox"></span>
              <span class="filter-option__label">cTrader</span>
            </label>
          </div>
        </div>

        <!-- Location -->
        <?php if ( ! empty( $locations ) ) : ?>
        <div class="filter-section">
          <div class="filter-section__header">
            <h3 class="filter-section__title"><?php esc_html_e( 'Location', 'onelisting' ); ?></h3>
            <svg class="filter-section__toggle" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
          <div class="filter-section__content">
            <?php foreach ( $locations as $loc ) : ?>
            <label class="filter-option" data-filter-type="location" data-filter-value="<?php echo esc_attr( $loc->slug ); ?>">
              <span class="filter-option__checkbox<?php echo ( $current_loc === (int) $loc->term_id ) ? ' checked' : ''; ?>"></span>
              <span class="filter-option__label"><?php echo esc_html( $loc->name ); ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Verified Only Toggle -->
        <div class="toggle-wrapper">
          <span class="toggle-label"><?php esc_html_e( 'Verified Only', 'onelisting' ); ?></span>
          <div class="toggle" data-filter-type="verifiedOnly">
            <div class="toggle__knob"></div>
          </div>
        </div>
      </div>
      <div class="filter-modal__footer">
        <button class="filters__apply"><?php esc_html_e( 'Apply Filters', 'onelisting' ); ?></button>
      </div>
    </div>
  </div>

<?php } ?>

</div>
