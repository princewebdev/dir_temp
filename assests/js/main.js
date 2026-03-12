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
  $$('.filter-section__header').forEach(header => {
    header.addEventListener('click', () => {
      const section = header.parentElement;
      const content = section.querySelector('.filter-section__content');
      const toggle = section.querySelector('.filter-section__toggle');
      
      content.classList.toggle('collapsed');
      toggle.classList.toggle('collapsed');
    });
  });
}

// ============================================
// Checkbox & Radio Filters
// ============================================
function initFilterOptions() {
  // Checkbox filters
  $$('.filter-option').forEach(option => {
    option.addEventListener('click', (e) => {
      e.preventDefault();
      const checkbox = option.querySelector('.filter-option__checkbox');
      if (!checkbox) return;
      
      // Single-select filters (directory, category, location)
      const filterType = option.dataset.filterType;
      const isSingleSelect = ['directory', 'category', 'location'].includes(filterType);
      
      if (isSingleSelect) {
        const section = option.closest('.filter-section__content');
        if (section) {
          section.querySelectorAll('.filter-option__checkbox').forEach(s => s.classList.remove('checked'));
        }
        checkbox.classList.add('checked');
      } else {
        checkbox.classList.toggle('checked');
      }
    });
  });
  
  // Rating radio options
  $$('.rating-option').forEach(option => {
    option.addEventListener('click', () => {
      const parent = option.closest('.rating-options');
      if (parent) {
        parent.querySelectorAll('.rating-option__radio').forEach(r => r.classList.remove('checked'));
      }
      option.querySelector('.rating-option__radio').classList.add('checked');
    });
  });
  
  // Toggle switches
  $$('.toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
      toggle.classList.toggle('active');
    });
  });
}

// ============================================
// Apply Filters - Gathers checked values and navigates
// ============================================
function initApplyFilters() {
  $$('.filters__apply').forEach(btn => {
    btn.addEventListener('click', () => {
      const url = new URL(window.location.href);
      
      // Clear existing filter params
      ['in_cat', 'in_loc', 'search_by_rating', 'directory_type',
       'regulation', 'platforms', 'spreads', 'account_type'].forEach(p => url.searchParams.delete(p));
      url.searchParams.delete('in_tag[]');
      
      const paramMap = {
        'directory': 'directory_type',
        'category': 'in_cat',
        'tag': 'in_tag[]',
        'location': 'in_loc',
        'regulation': 'regulation',
        'platforms': 'platforms',
        'spreads': 'spreads',
        'accountType': 'account_type',
      };
      
      // Gather checked filters from nearest container
      const container = btn.closest('.filters') || btn.closest('.filter-modal__content') || document;
      container.querySelectorAll('.filter-option__checkbox.checked').forEach(cb => {
        const option = cb.closest('.filter-option');
        if (!option) return;
        const type = option.dataset.filterType;
        const value = option.dataset.filterValue;
        if (!type || !value) return;
        
        const param = paramMap[type] || type;
        if (param === 'in_tag[]') {
          url.searchParams.append('in_tag[]', value);
        } else {
          url.searchParams.set(param, value);
        }
      });
      
      // Gather rating
      const checkedRating = container.querySelector('.rating-option__radio.checked');
      if (checkedRating) {
        const ratingOption = checkedRating.closest('.rating-option');
        if (ratingOption && ratingOption.dataset.rating) {
          url.searchParams.set('search_by_rating', ratingOption.dataset.rating);
        }
      }
      
      // Reset to page 1
      url.searchParams.delete('paged');
      
      window.location.href = url.toString();
    });
  });
}

// ============================================
// Clear Filters
// ============================================
function initClearFilters() {
  $$('.filters__clear').forEach(clearBtn => {
    clearBtn.addEventListener('click', () => {
      // Remove visual states
      $$('.filter-option__checkbox.checked').forEach(el => el.classList.remove('checked'));
      $$('.rating-option__radio.checked').forEach(el => el.classList.remove('checked'));
      $$('.toggle.active').forEach(el => el.classList.remove('active'));
      
      // Navigate to clean URL
      window.location.href = window.location.pathname;
    });
  });
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
  const filterBtn = $('.results__filters-btn');
  if (filterBtn) {
    filterBtn.addEventListener('click', openFilterModal);
  }
  
  const closeBtn = $('.filter-modal__close');
  if (closeBtn) {
    closeBtn.addEventListener('click', closeFilterModal);
  }
  
  const modal = $('#filter-modal');
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeFilterModal();
    });
  }
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeFilterModal();
  });
}

// ============================================
// Tabs - Navigate on click if data-href exists
// ============================================
function initTabs() {
  $$('.tabs__item').forEach(tab => {
    tab.addEventListener('click', (e) => {
      const href = tab.dataset.href;
      if (href) {
        e.preventDefault();
        window.location.href = href;
        return;
      }
      // Visual toggle fallback
      $$('.tabs__item').forEach(t => {
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
  $$('.results__view-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      // Navigate if data-href exists
      const href = btn.dataset.href;
      if (href) {
        e.preventDefault();
        window.location.href = href;
        return;
      }
      
      const view = btn.dataset.view;
      const resultsSection = btn.closest('.results') || btn.closest('section');
      
      // Update button states
      const toggleContainer = btn.closest('.results__view-toggle');
      if (toggleContainer) {
        toggleContainer.querySelectorAll('.results__view-btn').forEach(b => b.classList.remove('active'));
      }
      btn.classList.add('active');
      
      // Get containers
      const cardsList = resultsSection ? resultsSection.querySelector('.cards-list') : $('.cards-list');
      const cardsGrid = resultsSection ? resultsSection.querySelector('.cards-grid') : $('.cards-grid');
      
      if (cardsList) {
        cardsList.classList.remove('cards-list--grid', 'cards-list--compact');
        if (view === 'grid') cardsList.classList.add('cards-list--grid');
        else if (view === 'compact') cardsList.classList.add('cards-list--compact');
      }
      
      if (cardsGrid) {
        cardsGrid.classList.remove('cards-grid--list');
        if (view === 'list') cardsGrid.classList.add('cards-grid--list');
      }
    });
  });
}

// ============================================
// Sort - Navigate on select change
// ============================================
function initSort() {
  const sortSelect = $('.results__sort-select');
  if (sortSelect) {
    sortSelect.addEventListener('change', function() {
      if (this.value) {
        window.location.href = this.value;
      }
    });
  }
}

// ============================================
// Card Actions - Navigate on View Profile click
// ============================================
function initCardActions() {
  document.addEventListener('click', (e) => {
    const actionBtn = e.target.closest('.card__action');
    if (actionBtn) {
      const href = actionBtn.dataset.href;
      if (href) {
        window.location.href = href;
      }
    }
  });
}

// ============================================
// Bookmark Toggle
// ============================================
function initBookmarks() {
  document.addEventListener('click', (e) => {
    const bookmarkBtn = e.target.closest('.card__bookmark');
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
  $$('.pagination__btn:not([disabled])').forEach(btn => {
    btn.addEventListener('click', function() {
      if (this.textContent.trim().match(/^\d+$/)) {
        $$('.pagination__btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      }
    });
  });
}

// ============================================
// Location Search Filter
// ============================================
function initLocationSearch() {
  $$('.search-location input').forEach(input => {
    input.addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const section = this.closest('.filter-section__content');
      if (!section) return;
      section.querySelectorAll('.filter-option[data-filter-type="location"]').forEach(option => {
        const label = option.querySelector('.filter-option__label');
        if (label) {
          option.style.display = label.textContent.toLowerCase().includes(query) ? '' : 'none';
        }
      });
    });
  });
}

// ============================================
// Initialize All
// ============================================
function init() {
  initFilterToggles();
  initFilterOptions();
  initApplyFilters();
  initClearFilters();
  initMobileFilters();
  initTabs();
  initViewToggle();
  initSort();
  initCardActions();
  initBookmarks();
  initPagination();
  initLocationSearch();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', init);


// FAQ Toggle
    document.querySelectorAll('.faq-item__question').forEach(button => {
      button.addEventListener('click', () => {
        const item = button.parentElement;
        item.classList.toggle('faq-item--expanded');
      });
    });

    // Media Gallery Image Switching
    document.querySelectorAll('.media-gallery__thumb').forEach(thumb => {
      thumb.addEventListener('click', () => {
        const gallery = thumb.closest('.media-gallery');
        const mainImg = gallery.querySelector('.media-gallery__main-img');
        const fullSrc = thumb.dataset.full;
        
        if (fullSrc && mainImg) {
          mainImg.src = fullSrc;
          // Update active state
          gallery.querySelectorAll('.media-gallery__thumb').forEach(t => t.classList.remove('media-gallery__thumb--active'));
          thumb.classList.add('media-gallery__thumb--active');
        }
      });
    });
