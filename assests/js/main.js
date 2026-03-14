/**
 * Forex Industry Directory - UI Interactions
 * Handles filter toggles, mobile modal, tabs, range sliders, and filter submission.
 */

// ============================================
// DOM Helpers
// ============================================
const $ = (selector, context = document) => context.querySelector(selector);
const $$ = (selector, context = document) => [...context.querySelectorAll(selector)];

// ============================================
// Filter Section Toggle
// ============================================
function initFilterToggles() {
  $$('.pwdev-filter-section__header').forEach(header => {
    header.addEventListener('click', () => {
      const section = header.parentElement;
      const content = section.querySelector('.pwdev-filter-section__content');
      const toggle = section.querySelector('.pwdev-filter-section__toggle');
      if (content) content.classList.toggle('collapsed');
      if (toggle)  toggle.classList.toggle('collapsed');
    });
  });
}

// ============================================
// Checkbox & Radio Filters (UI only)
// ============================================
function initFilterOptions() {
  // Checkbox filters
  $$('.pwdev-filter-option').forEach(option => {
    option.addEventListener('click', () => {
      const checkbox = option.querySelector('.pwdev-filter-option__checkbox');
      if (checkbox) {
        checkbox.classList.toggle('checked');
      }
    });
  });
  
  // Rating radio options
  $$('.pwdev-rating-option').forEach(option => {
    option.addEventListener('click', () => {
      // Remove checked from all radios in the same group
      const parent = option.closest('.pwdev-rating-options');
      if (parent) {
        parent.querySelectorAll('.pwdev-rating-option__radio').forEach(r => r.classList.remove('checked'));
      }
      option.querySelector('.pwdev-rating-option__radio').classList.add('checked');
    });
  });
  
  // Toggle switches
  $$('.pwdev-toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
      toggle.classList.toggle('active');
    });
  });
}

// ============================================
// Clear Filters
// ============================================
function initClearFilters() {
  const clearBtn = $('.pwdev-filters__clear');
  if (!clearBtn) return;
  // If it's an <a> tag, let normal link navigation handle it
  if (clearBtn.tagName === 'A') return;
  clearBtn.addEventListener('click', () => {
    const url = new URL(window.location.href);
    ['q','in_cat','in_loc','in_tag','search_by_rating','directory_type','search_radius','verified_only','paged']
      .forEach(p => url.searchParams.delete(p));
    [...url.searchParams.keys()].filter(k => k.startsWith('custom_field')).forEach(k => url.searchParams.delete(k));
    window.location.href = url.toString();
  });
}

// ============================================
// Range Sliders — drag-to-filter (dual and single thumb)
// ============================================
function initRangeSliders() {
  $$('.pwdev-range-slider').forEach(slider => {
    const track    = slider.querySelector('.pwdev-range-slider__track');
    const fill     = slider.querySelector('.pwdev-range-slider__fill');
    const minThumb = slider.querySelector('[data-thumb="min"]');
    const maxThumb = slider.querySelector('[data-thumb="max"]');
    const minLabel = slider.querySelector('.pwdev-range-slider__value--min');
    const maxLabel = slider.querySelector('.pwdev-range-slider__value--max');
    const minInput = slider.querySelector('.pwdev-range-slider__input-min');
    const maxInput = slider.querySelector('.pwdev-range-slider__input-max');
    if (!track || !maxThumb) return;

    const dataMin = parseFloat(slider.dataset.min  != null ? slider.dataset.min  : 0);
    const dataMax = parseFloat(slider.dataset.max  != null ? slider.dataset.max  : 100);
    const prefix  = slider.dataset.prefix || '';
    const suffix  = slider.dataset.suffix || '';
    let currentMin = parseFloat(slider.dataset.currentMin != null ? slider.dataset.currentMin : dataMin);
    let currentMax = parseFloat(slider.dataset.currentMax != null ? slider.dataset.currentMax : dataMax);

    function fmt(v) { return prefix + Math.round(v).toLocaleString() + suffix; }

    function updateUI() {
      const range  = dataMax - dataMin || 1;
      const minPct = ((currentMin - dataMin) / range) * 100;
      const maxPct = ((currentMax - dataMin) / range) * 100;
      if (minThumb) minThumb.style.left = minPct + '%';
      maxThumb.style.left = maxPct + '%';
      if (fill) {
        fill.style.left  = (minThumb ? minPct : 0) + '%';
        fill.style.width = (minThumb ? (maxPct - minPct) : maxPct) + '%';
      }
      if (minLabel) minLabel.textContent = fmt(currentMin);
      if (maxLabel) maxLabel.textContent = fmt(currentMax);
      if (minInput) minInput.value = Math.round(currentMin);
      if (maxInput) maxInput.value = Math.round(currentMax);
    }
    updateUI();

    function getValueFromEvent(e) {
      const rect    = track.getBoundingClientRect();
      const clientX = e.touches ? e.touches[0].clientX : e.clientX;
      const pct     = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
      return dataMin + pct * (dataMax - dataMin);
    }

    function makeDraggable(thumb, isMin) {
      if (!thumb) return;
      thumb.style.cursor      = 'grab';
      thumb.style.touchAction = 'none';
      function onMove(e) {
        e.preventDefault();
        const val = getValueFromEvent(e);
        if (isMin) {
          currentMin = Math.max(dataMin, Math.min(val, currentMax - 1));
        } else {
          currentMax = Math.min(dataMax, Math.max(val, minThumb ? currentMin + 1 : dataMin));
        }
        updateUI();
      }
      function onEnd() {
        thumb.style.cursor = 'grab';
        document.removeEventListener('mousemove', onMove);
        document.removeEventListener('mouseup',   onEnd);
        document.removeEventListener('touchmove', onMove);
        document.removeEventListener('touchend',  onEnd);
      }
      thumb.addEventListener('mousedown', e => {
        e.preventDefault();
        thumb.style.cursor = 'grabbing';
        document.addEventListener('mousemove', onMove);
        document.addEventListener('mouseup',   onEnd);
      });
      thumb.addEventListener('touchstart', e => {
        e.preventDefault();
        document.addEventListener('touchmove', onMove, { passive: false });
        document.addEventListener('touchend',  onEnd);
      }, { passive: false });
    }
    makeDraggable(minThumb, true);
    makeDraggable(maxThumb, false);
  });
}

// ============================================
// Location Search — text-filter visible location options
// ============================================
function initLocationSearch() {
  $$('.pwdev-search-location input').forEach(input => {
    input.addEventListener('input', () => {
      const term      = input.value.toLowerCase().trim();
      const container = input.closest('.pwdev-filter-section__content');
      if (!container) return;
      container.querySelectorAll('.pwdev-filter-option').forEach(opt => {
        const label = opt.querySelector('.pwdev-filter-option__label');
        if (!label) return;
        opt.style.display = (!term || label.textContent.toLowerCase().includes(term)) ? '' : 'none';
      });
    });
  });
}

// ============================================
// Apply Filters — collect state, build URL, navigate
// ============================================
function initApplyFilters() {
  $$('.pwdev-filters__apply').forEach(btn => {
    btn.addEventListener('click', () => {
      const url = new URL(window.location.href);
      // Remove existing meta/filter params
      [...url.searchParams.keys()]
        .filter(k => k.startsWith('custom_field') || k === 'search_radius' || k === 'verified_only' || k === 'paged')
        .forEach(k => url.searchParams.delete(k));

      // Collect checked meta-filter options grouped by data-filter-type
      const filtersByType = {};
      $$('.pwdev-filter-option[data-filter-type]').forEach(opt => {
        const type    = opt.dataset.filterType;
        const value   = opt.dataset.filterValue;
        const cbx     = opt.querySelector('.pwdev-filter-option__checkbox');
        const checked = cbx && cbx.classList.contains('checked');
        if (type && value && checked) {
          if (!filtersByType[type]) filtersByType[type] = [];
          if (!filtersByType[type].includes(value)) filtersByType[type].push(value);
        }
      });

      // Append as custom_field[key][]
      ['regulation', 'trading_platforms', 'spreads_from', 'account_type'].forEach(type => {
        (filtersByType[type] || []).forEach(v => url.searchParams.append('custom_field[' + type + '][]', v));
      });

      // Deposit range → custom_field[min_deposit]=min-max
      $$('.pwdev-range-slider[data-slider-type="deposit"]').forEach(slider => {
        const minI = slider.querySelector('.pwdev-range-slider__input-min');
        const maxI = slider.querySelector('.pwdev-range-slider__input-max');
        if (minI && maxI) {
          if (minI.value !== slider.dataset.min || maxI.value !== slider.dataset.max) {
            url.searchParams.set('custom_field[min_deposit]', minI.value + '-' + maxI.value);
          }
        }
      });

      // Radius slider → search_radius
      $$('.pwdev-range-slider[data-slider-type="radius"]').forEach(slider => {
        const maxI = slider.querySelector('.pwdev-range-slider__input-max');
        if (maxI) url.searchParams.set('search_radius', maxI.value);
      });

      // Verified only toggle
      const vt = $('.pwdev-toggle[data-filter-type="verifiedOnly"]');
      if (vt && vt.classList.contains('active')) url.searchParams.set('verified_only', '1');

      window.location.href = url.toString();
    });
  });
}

// ============================================
// Mobile Filter Modal
// ============================================
function openFilterModal() {
  const modal = $('#filter-modal') || $('#pwdev-filter-modal');
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeFilterModal() {
  const modal = $('#filter-modal') || $('#pwdev-filter-modal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

function initMobileFilters() {
  const filterBtn = $('.pwdev-results__filters-btn');
  if (filterBtn) filterBtn.addEventListener('click', openFilterModal);
  const closeBtn = $('.pwdev-filter-modal__close');
  if (closeBtn) closeBtn.addEventListener('click', closeFilterModal);
  const modal = $('#filter-modal') || $('#pwdev-filter-modal');
  if (modal) modal.addEventListener('click', e => { if (e.target === modal) closeFilterModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeFilterModal(); });
}

// ============================================
// Tabs
// ============================================
function initTabs() {
  $$('.pwdev-tabs__item').forEach(tab => {
    tab.addEventListener('click', () => {
      // Update active state
      $$('.pwdev-tabs__item').forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
    });
  });
}

// ============================================
// View Toggle (Grid/List/Compact)
// ============================================
function initViewToggle() {
  $$('.pwdev-results__view-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const view = btn.dataset.view;
      const resultsSection = btn.closest('.pwdev-results') || btn.closest('section');
      
      // Update button states
      const toggleContainer = btn.closest('.pwdev-results__view-toggle');
      if (toggleContainer) {
        toggleContainer.querySelectorAll('.pwdev-results__view-btn').forEach(b => b.classList.remove('active'));
      }
      btn.classList.add('active');
      
      // Get containers - try cards-list first (search results), then cards-grid (homepage)
      const cardsList = resultsSection ? resultsSection.querySelector('.pwdev-cards-list') : $('.pwdev-cards-list');
      const cardsGrid = resultsSection ? resultsSection.querySelector('.pwdev-cards-grid') : $('.pwdev-cards-grid');
      
      // Handle cards-list container (search results page)
      if (cardsList) {
        cardsList.classList.remove('pwdev-cards-list--grid', 'pwdev-cards-list--compact');
        if (view === 'grid') {
          cardsList.classList.add('pwdev-cards-list--grid');
        } else if (view === 'compact') {
          cardsList.classList.add('pwdev-cards-list--compact');
        }
      }
      
      // Handle cards-grid container (homepage)
      if (cardsGrid) {
        cardsGrid.classList.remove('pwdev-cards-grid--list');
        if (view === 'list') {
          cardsGrid.classList.add('pwdev-cards-grid--list');
        }
      }
    });
  });
}

// ============================================
// Bookmark Toggle
// ============================================
function initBookmarks() {
  document.addEventListener('click', (e) => {
    const bookmarkBtn = e.target.closest('.pwdev-card__bookmark');
    if (bookmarkBtn) {
      e.preventDefault();
      bookmarkBtn.classList.toggle('bookmarked');
    }
  });
}

// ============================================
// Pagination Active State
// ============================================
function initPagination() {
  $$('.pwdev-pagination__btn:not([disabled])').forEach(btn => {
    btn.addEventListener('click', function() {
      // Only for numbered buttons (not prev/next with SVGs)
      if (this.textContent.trim().match(/^\d+$/)) {
        $$('.pwdev-pagination__btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      }
    });
  });
}

// ============================================
// Directorist Profile Page
// ============================================
function initProfilePageInteractions() {
  if (!$('.pwdev-profile-content')) {
    return;
  }

  $$('.pwdev-faq-item__question').forEach(button => {
    button.addEventListener('click', () => {
      const item = button.closest('.pwdev-faq-item');
      if (item) {
        const answer = item.querySelector('.pwdev-faq-item__answer');
        const isExpanded = item.classList.toggle('pwdev-faq-item--expanded');

        button.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
        if (answer) {
          answer.hidden = !isExpanded;
        }
      }
    });
  });

  $$('.pwdev-media-gallery__thumb').forEach((thumb, index) => {
    if (index === 0) {
      thumb.classList.add('pwdev-media-gallery__thumb--active');
    }

    thumb.addEventListener('click', () => {
      const gallery = thumb.closest('.pwdev-media-gallery');
      if (!gallery) {
        return;
      }

      const mainImg = gallery.querySelector('.pwdev-media-gallery__main-img');
      const fullSrc = thumb.dataset.full;

      if (fullSrc && mainImg) {
        mainImg.src = fullSrc;
        $$('.pwdev-media-gallery__thumb', gallery).forEach(t => t.classList.remove('pwdev-media-gallery__thumb--active'));
        thumb.classList.add('pwdev-media-gallery__thumb--active');
      }
    });
  });
}

// ============================================
// Initialize All
// ============================================
function init() {
  initFilterToggles();
  initFilterOptions();
  initClearFilters();
  initRangeSliders();
  initLocationSearch();
  initApplyFilters();
  initMobileFilters();
  initTabs();
  initViewToggle();
  initBookmarks();
  initPagination();
  initProfilePageInteractions();
}
document.addEventListener('DOMContentLoaded', init);
