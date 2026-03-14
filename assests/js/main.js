/**
 * Forex Industry Directory - UI Interactions
 * Handles filter toggles, mobile modal, tabs, and other UI interactions
 * Dynamic content will be handled by PHP
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
      
      content.classList.toggle('collapsed');
      toggle.classList.toggle('collapsed');
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
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      // Uncheck all checkboxes
      $$('.pwdev-filter-option__checkbox.checked').forEach(el => el.classList.remove('checked'));
      // Uncheck all radios
      $$('.pwdev-rating-option__radio.checked').forEach(el => el.classList.remove('checked'));
      // Reset toggles
      $$('.pwdev-toggle.active').forEach(el => el.classList.remove('active'));
    });
  }
}

// ============================================
// Mobile Filter Modal
// ============================================
function openFilterModal() {
  const modal = $('#filter-modal');
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeFilterModal() {
  const modal = $('#filter-modal');
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

function initMobileFilters() {
  // Open modal button
  const filterBtn = $('.pwdev-results__filters-btn');
  if (filterBtn) {
    filterBtn.addEventListener('click', openFilterModal);
  }
  
  // Close button
  const closeBtn = $('.pwdev-filter-modal__close');
  if (closeBtn) {
    closeBtn.addEventListener('click', closeFilterModal);
  }
  
  // Close on backdrop click
  const modal = $('#filter-modal');
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeFilterModal();
    });
  }
  
  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeFilterModal();
  });
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
        item.classList.toggle('pwdev-faq-item--expanded');
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
  initMobileFilters();
  initTabs();
  initViewToggle();
  initBookmarks();
  initPagination();
  initProfilePageInteractions();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', init);
