<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

get_header( 'directorist' );


?>

  <!-- Breadcrumb -->
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <div class="container">
      <ol class="breadcrumb__list">
        <li class="breadcrumb__item">
          <a href="index.html" class="breadcrumb__link">
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </a>
        </li>
        <li class="breadcrumb__separator">›</li>
        <li class="breadcrumb__item">
          <a href="index.html" class="breadcrumb__link">Directory</a>
        </li>
        <li class="breadcrumb__separator">›</li>
        <li class="breadcrumb__item">
          <a href="search-results.html" class="breadcrumb__link">Brokers</a>
        </li>
        <li class="breadcrumb__separator">›</li>
        <li class="breadcrumb__item breadcrumb__item--current">IG Group</li>
      </ol>
      <a href="search-results.html" class="breadcrumb__back">
        <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Results
      </a>
    </div>
  </nav>

  <!-- Profile Hero -->
  <header class="profile-hero">
    <div class="profile-hero__banner">
      <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1400&h=250&fit=crop" alt="Trading background" class="profile-hero__banner-img">
    </div>
    <div class="container">
      <div class="profile-hero__content">
        <div class="profile-hero__logo-wrapper">
          <div class="profile-hero__logo">IG</div>
        </div>
        <div class="profile-hero__info">
          <div class="profile-hero__title-row">
            <h1 class="profile-hero__name">IG Group</h1>
            <span class="verification-badge verification-badge--verified">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
              Verified
            </span>
            <span class="verification-badge verification-badge--featured">FEATURED</span>
          </div>
          <div class="profile-hero__meta">
            <div class="profile-hero__rating">
              <div class="card__stars">
                <span class="card__star">★</span>
                <span class="card__star">★</span>
                <span class="card__star">★</span>
                <span class="card__star">★</span>
                <span class="card__star empty">☆</span>
              </div>
              <span class="profile-hero__reviews">(87 reviews)</span>
            </div>
            <span class="profile-hero__separator">·</span>
            <span class="profile-hero__type">Forex Broker</span>
            <span class="profile-hero__separator">·</span>
            <span class="profile-hero__location">
              <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
              </svg>
              London, UK
            </span>
          </div>
        </div>
        <div class="profile-hero__actions">
          <button class="btn btn--primary btn--lg">More Info</button>
          <button class="btn btn--icon" aria-label="Share">
            <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
            </svg>
          </button>
          <button class="btn btn--icon" aria-label="Bookmark">
            <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
          </button>
          <button class="btn btn--icon" aria-label="Report">
            <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="profile-content">
    <div class="container">
      <div class="profile-layout">
        <!-- Main Column -->
        <div class="profile-main">
          <!-- Overview Section -->
          <section class="profile-section">
            <h2 class="profile-section__title">Overview</h2>
            <div class="overview-grid overview-grid--with-icons">
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Year Founded</span>
                  <span class="overview-item__value">1974</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Min Deposit</span>
                  <span class="overview-item__value">$250</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Execution Type</span>
                  <span class="overview-item__value">Market Maker / DMA</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Headquarters</span>
                  <span class="overview-item__value">London, United Kingdom</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Max Leverage</span>
                  <span class="overview-item__value">1:30 (Retail)</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Asset Classes</span>
                  <span class="overview-item__value">17,000+ instruments</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Regulation</span>
                  <div class="overview-item__badges">
                    <span class="badge badge--fca">FCA</span>
                    <span class="badge badge--asic">ASIC</span>
                  </div>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Trading Platforms</span>
                  <div class="overview-item__badges">
                    <span class="badge badge--mt4">MT4</span>
                    <span class="badge badge--l2">L2</span>
                  </div>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Payment Methods</span>
                  <span class="overview-item__value">Card, Bank, PayPal</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Publicly Listed</span>
                  <span class="overview-item__value">LSE: IGG</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Spreads From</span>
                  <span class="overview-item__value">0.6 pips</span>
                </div>
              </div>
              <div class="overview-item">
                <div class="overview-item__icon">
                  <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                  </svg>
                </div>
                <div class="overview-item__content">
                  <span class="overview-item__label">Countries Served</span>
                  <span class="overview-item__value">150+ countries</span>
                </div>
              </div>
            </div>
          </section>

          <!-- About Section -->
          <section class="profile-section">
            <h2 class="profile-section__title">About IG Group</h2>
            <div class="profile-section__content">
              <p>IG Group is a global leader in online trading, providing access to over 17,000 financial markets including forex, shares, indices, and commodities. Founded in 1974, IG has grown to become one of the world's largest CFD brokers with offices in 18 countries.</p>
              <p>The company is listed on the London Stock Exchange (LSE: IGG) and is regulated by multiple tier-1 authorities including the FCA, ASIC, and BaFin. IG offers a proprietary trading platform alongside MetaTrader 4, with award-winning research and educational resources.</p>
            </div>
          </section>

          <!-- IG Group Review 2025 -->
          <section class="profile-section">
            <h2 class="profile-section__title">IG Group Review 2025</h2>
            <div class="profile-section__meta">
              <span class="author">By <strong>Leaprate Editorial</strong></span>
              <span class="separator">·</span>
              <span class="date">Updated Feb 28, 2025</span>
              <span class="separator">·</span>
              <span class="read-time">8 min read</span>
            </div>
            
            <div class="review-content">
              <h3>Overview</h3>
              <p>IG Group remains one of the most established and trusted names in online trading. With nearly 50 years of history, the broker has built a reputation for reliability, innovation, and regulatory compliance across multiple jurisdictions worldwide.</p>
              
              <h3>Trading Experience</h3>
              <p>The proprietary web platform delivers a clean, intuitive interface with powerful charting tools powered by TradingView. Execution speeds are consistently fast, with minimal slippage even during volatile market conditions. The mobile app mirrors the desktop experience well, making it easy to manage positions on the go.</p>
              
              <h3>Fees & Pricing</h3>
              <p>Spreads on major forex pairs start from 0.6 pips, which is competitive for a broker of this size. There are no commissions on CFD trades for most instruments. Overnight funding rates are transparent and in line with industry standards. Overall, IG offers fair value for the breadth of markets and tools provided.</p>
            </div>
          </section>

          <!-- Press Releases -->
          <section class="profile-section">
            <div class="profile-section__header">
              <h2 class="profile-section__title">Press Releases</h2>
              <a href="#" class="profile-section__link">View All</a>
            </div>
            <div class="press-releases">
              <article class="press-release">
                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=80&h=80&fit=crop" alt="" class="press-release__image">
                <div class="press-release__content">
                  <h4 class="press-release__title">IG Group Reports Record Q4 Revenue Amid Growing Retail Trading Volumes</h4>
                  <div class="press-release__meta">
                    <span>Feb 15, 2025</span>
                    <span class="press-release__tag">Earnings</span>
                  </div>
                </div>
              </article>
              <article class="press-release">
                <img src="https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=80&h=80&fit=crop" alt="" class="press-release__image">
                <div class="press-release__content">
                  <h4 class="press-release__title">IG Launches New ESG Indices for Sustainable Investing in Partnership with MSCI</h4>
                  <div class="press-release__meta">
                    <span>Jan 30, 2025</span>
                    <span class="press-release__tag">Product</span>
                  </div>
                </div>
              </article>
              <article class="press-release">
                <img src="https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=80&h=80&fit=crop" alt="" class="press-release__image">
                <div class="press-release__content">
                  <h4 class="press-release__title">IG Group Expands Cryptocurrency Offering With 11 New Digital Asset CFDs</h4>
                  <div class="press-release__meta">
                    <span>Jan 12, 2025</span>
                    <span class="press-release__tag">Crypto</span>
                  </div>
                </div>
              </article>
              <article class="press-release">
                <img src="https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=80&h=80&fit=crop" alt="" class="press-release__image">
                <div class="press-release__content">
                  <h4 class="press-release__title">IG Named Best Multi-Platform Broker at Finance Magnates Awards 2024</h4>
                  <div class="press-release__meta">
                    <span>Dec 5, 2024</span>
                    <span class="press-release__tag">Awards</span>
                  </div>
                </div>
              </article>
            </div>
          </section>

          <!-- Screenshots & Media -->
          <section class="profile-section">
            <div class="profile-section__header">
              <h2 class="profile-section__title">Screenshots & Media</h2>
              <span class="profile-section__count profile-section__count--red">6 images</span>
            </div>
            <div class="media-gallery media-gallery--split" id="mediaGallery">
              <div class="media-gallery__main">
                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=600&fit=crop" alt="Trading Platform Screenshot" class="media-gallery__main-img" id="galleryMainImg">
              </div>
              <div class="media-gallery__sidebar">
                <div class="media-gallery__thumb media-gallery__thumb--active" data-full="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=600&fit=crop">
                  <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=200&h=130&fit=crop" alt="Screenshot 1">
                </div>
                <div class="media-gallery__thumb" data-full="https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=800&h=600&fit=crop">
                  <img src="https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=200&h=130&fit=crop" alt="Screenshot 2">
                </div>
                <div class="media-gallery__thumb" data-full="https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=800&h=600&fit=crop">
                  <img src="https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=200&h=130&fit=crop" alt="Screenshot 3">
                </div>
                <div class="media-gallery__thumb" data-full="https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=800&h=600&fit=crop">
                  <img src="https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=200&h=130&fit=crop" alt="Screenshot 4">
                </div>
                <div class="media-gallery__thumb" data-full="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop">
                  <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=200&h=130&fit=crop" alt="Screenshot 5">
                </div>
              </div>
            </div>
          </section>

          <!-- Broker Overview Video -->
          <section class="profile-section">
            <h2 class="profile-section__title">Broker Overview Video</h2>
            <div class="video-container video-container--fullwidth">
              <div class="video-embed">
                <iframe 
                  src="https://www.youtube.com/embed/6adnW--HY6A" 
                  title="IG Group Platform Tour & Trading Features"
                  frameborder="0" 
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                  allowfullscreen>
                </iframe>
              </div>
              <p class="video-caption">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                </svg>
                IG Group Platform Tour & Trading Features
                <a href="https://youtu.be/6adnW--HY6A?si=xpBd6e2Yvg7GTE-x" target="_blank" rel="noopener" class="video-link">Watch on YouTube</a>
              </p>
            </div>
          </section>

          <!-- Reviews & Ratings -->
          <section class="profile-section">
            <h2 class="profile-section__title">Reviews & Ratings</h2>
            <div class="reviews-summary reviews-summary--left">
              <div class="reviews-summary__stars">
                <div class="card__stars card__stars--lg">
                  <span class="card__star">★</span>
                  <span class="card__star">★</span>
                  <span class="card__star">★</span>
                  <span class="card__star">★</span>
                  <span class="card__star empty">☆</span>
                </div>
              </div>
              <span class="reviews-summary__count">Based on 87 Reviews</span>
            </div>
          </section>

          <!-- Location -->
          <section class="profile-section">
            <h2 class="profile-section__title">Location</h2>
            <div class="location-map">
              <div class="location-map__embed">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.542152777686!2d-0.09235068422955559!3d51.51130497963622!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4876034add58c38f%3A0xc26dc7f6d3d4c4e!2sCannon%20Bridge%20House!5e0!3m2!1sen!2suk!4v1647856800000!5m2!1sen!2suk" 
                  width="100%" 
                  height="300" 
                  style="border:0; border-radius: 8px;" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>
              <div class="location-details">
                <p class="location-details__address">
                  <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                  </svg>
                  Cannon Bridge House, 25 Dowgate Hill, London EC4R 2YA, United Kingdom
                </p>
                <a href="https://maps.google.com/?q=Cannon+Bridge+House+London" target="_blank" rel="noopener" class="location-details__link">
                  Open in Google Maps
                  <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                  </svg>
                </a>
              </div>
            </div>
          </section>

          <!-- FAQ -->
          <section class="profile-section">
            <h2 class="profile-section__title">Frequently Asked Questions</h2>
            <div class="faq-list">
              <div class="faq-item">
                <button class="faq-item__question">
                  <span>Is IG Group regulated?</span>
                  <svg class="faq-item__icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"/>
                  </svg>
                </button>
                <div class="faq-item__answer">
                  <p>Yes, IG Group is regulated by multiple tier-1 authorities including the FCA (UK), ASIC (Australia), and BaFin (Germany).</p>
                </div>
              </div>
              <div class="faq-item faq-item--expanded">
                <button class="faq-item__question">
                  <span>What is the minimum deposit at IG?</span>
                  <svg class="faq-item__icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"/>
                  </svg>
                </button>
                <div class="faq-item__answer">
                  <p>The minimum deposit at IG Group is $250 for a standard trading account. However, there is no minimum deposit for a share dealing account. Bank transfer deposits have no minimum, while card deposits require at least $300.</p>
                </div>
              </div>
              <div class="faq-item">
                <button class="faq-item__question">
                  <span>What platforms does IG support?</span>
                  <svg class="faq-item__icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"/>
                  </svg>
                </button>
                <div class="faq-item__answer">
                  <p>IG supports MT4, ProRealTime, and their proprietary web and mobile platforms.</p>
                </div>
              </div>
              <div class="faq-item">
                <button class="faq-item__question">
                  <span>Does IG offer a demo account?</span>
                  <svg class="faq-item__icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"/>
                  </svg>
                </button>
                <div class="faq-item__answer">
                  <p>Yes, IG offers a free demo account with virtual funds to practice trading.</p>
                </div>
              </div>
            </div>
          </section>
        </div>

        <!-- Sidebar -->
        <aside class="profile-sidebar">
          <!-- Contact Information -->
          <div class="sidebar-card">
            <h3 class="sidebar-card__title">Contact Information</h3>
            <ul class="contact-list">
              <li class="contact-list__item">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <span>+44 20 7896 0011</span>
              </li>
              <li class="contact-list__item">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                </svg>
                <a href="mailto:info@ig.com">info@ig.com</a>
              </li>
              <li class="contact-list__item">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                <a href="https://www.ig.com" target="_blank" rel="noopener">www.ig.com</a>
              </li>
              <li class="contact-list__item">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                <span>London, United Kingdom</span>
              </li>
            </ul>
            <a href="https://www.ig.com" target="_blank" rel="noopener" class="btn btn--dark btn--block">
              <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
              </svg>
              Visit Website
            </a>
          </div>

          <!-- Social & Messengers -->
          <div class="sidebar-card">
            <h3 class="sidebar-card__title">Social & Messengers</h3>
            <h4 class="sidebar-card__subtitle sidebar-card__subtitle--first">Social Media</h4>
            <div class="social-links">
              <a href="#" class="social-link social-link--neutral" aria-label="LinkedIn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
              <a href="#" class="social-link social-link--neutral" aria-label="Twitter">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
              </a>
              <a href="#" class="social-link social-link--neutral" aria-label="Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
              <a href="#" class="social-link social-link--neutral" aria-label="YouTube">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </a>
              <a href="#" class="social-link social-link--neutral" aria-label="Instagram">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
              </a>
            </div>
            <h4 class="sidebar-card__subtitle">Messengers</h4>
            <div class="messenger-links messenger-links--inline">
              <a href="#" class="messenger-link messenger-link--whatsapp">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
              </a>
              <a href="#" class="messenger-link messenger-link--telegram">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                </svg>
                Telegram
              </a>
              <a href="#" class="messenger-link messenger-link--skype">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12.069 18.874c-4.023 0-5.82-1.979-5.82-3.464 0-.765.561-1.296 1.333-1.296 1.723 0 1.273 2.477 4.487 2.477 1.641 0 2.55-.895 2.55-1.811 0-.551-.269-1.16-1.354-1.429l-3.576-.895c-2.88-.724-3.403-2.286-3.403-3.751 0-3.047 2.861-4.191 5.549-4.191 2.471 0 5.393 1.373 5.393 3.199 0 .784-.688 1.24-1.453 1.24-1.469 0-1.198-2.037-4.164-2.037-1.469 0-2.292.664-2.292 1.617s1.153 1.258 2.157 1.487l2.637.587c2.891.649 3.624 2.346 3.624 3.944 0 2.476-1.902 4.324-5.722 4.324m11.084-4.882l-.029.135-.044-.24c.015.045.044.074.059.12.12-.675.181-1.363.181-2.052 0-1.529-.301-3.012-.898-4.42-.569-1.348-1.395-2.562-2.427-3.596-1.049-1.033-2.247-1.856-3.595-2.426C15.015.301 13.531 0 12 0c-.534 0-1.057.036-1.569.109-.481-.073-.985-.109-1.485-.109C7.231 0 5.691.475 4.341 1.328A8.902 8.902 0 00.972 5.391 9.04 9.04 0 000 9.042c0 .533.045 1.057.135 1.569-.04.361-.074.734-.074 1.093 0 1.529.301 3.012.898 4.42.569 1.348 1.395 2.562 2.427 3.596 1.049 1.034 2.247 1.857 3.595 2.427 1.405.598 2.889.898 4.419.898.534 0 1.057-.045 1.569-.135.473.074.988.135 1.485.135 1.713 0 3.253-.465 4.603-1.318a8.903 8.903 0 003.369-4.063 9.04 9.04 0 00.972-4.065c0-.52-.045-1.033-.12-1.545"/>
                </svg>
                Skype
              </a>
            </div>
          </div>

          <!-- Business Hours -->
          <div class="sidebar-card">
            <div class="sidebar-card__header">
              <h3 class="sidebar-card__title">Business Hours</h3>
              <span class="status-indicator status-indicator--available"><span class="status-dot"></span>Open Now</span>
            </div>
            <ul class="hours-list">
              <li class="hours-list__item">
                <span>Monday</span>
                <span>9:00 AM - 5:00 PM</span>
              </li>
              <li class="hours-list__item">
                <span>Tuesday</span>
                <span>9:00 AM - 5:00 PM</span>
              </li>
              <li class="hours-list__item">
                <span>Wednesday</span>
                <span>9:00 AM - 5:00 PM</span>
              </li>
              <li class="hours-list__item">
                <span>Thursday</span>
                <span>9:00 AM - 5:00 PM</span>
              </li>
              <li class="hours-list__item">
                <span>Friday</span>
                <span>9:00 AM - 5:00 PM</span>
              </li>
              <li class="hours-list__item">
                <span>Saturday</span>
                <span class="hours-list__closed">Closed</span>
              </li>
              <li class="hours-list__item">
                <span>Sunday</span>
                <span class="hours-list__closed">Closed</span>
              </li>
            </ul>
          </div>

          <!-- Contact Form -->
          <div class="sidebar-card">
            <h3 class="sidebar-card__title">Contact IG Group</h3>
            <form class="contact-form">
              <div class="form-group">
                <label class="form-label">Your Name</label>
                <input type="text" class="form-input" placeholder="John Smith">
              </div>
              <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" placeholder="john@example.com">
              </div>
              <div class="form-group">
                <label class="form-label">Message</label>
                <textarea class="form-textarea" rows="4" placeholder="Your message..."></textarea>
              </div>
              <button type="submit" class="btn btn--success btn--block">Send Message</button>
            </form>
          </div>

          <!-- Is this your listing? -->
          <div class="sidebar-card sidebar-card--claim">
            <div class="sidebar-card__icon">
              <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
              </svg>
            </div>
            <h3 class="sidebar-card__title sidebar-card__title--center">Is this your listing?</h3>
            <p class="sidebar-card__text sidebar-card__text--center">Claim it to update information and respond to reviews.</p>
            <a href="#" class="btn btn--outline-primary btn--block">Claim This Listing</a>
          </div>
        </aside>
      </div>
    </div>
  </main>


<?php
get_footer( 'directorist' );