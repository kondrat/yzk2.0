/*
 * jQuery Universal Paginate
 * version: 1.0.0 (2010-11-02)
 * @requires jQuery v1.4.2 or later
 * @requires jQuery Templates
 *
 * Examples and documentation at: http://blog.pierrejeanparra.com/jquery-plugins/universal-paginate/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
;(function($) {
	
	$.fn.universalPaginate = function(options) {
		var params;
		
		if (!this.length) {
			return this;
		}
			
		$.universalPaginate = {
			defaults: {
				nbItemsByPage: 10,
				nbPagesDisplayed: 10,
				itemTemplate: '<li>${value}</li>',
				dataUrl: null,
				refreshInterval: null,
				universalPaginateClass: 'universal_paginate',
				controlsPosition: 'top',
				paginationNavigationArrows: true,
				allowItemsByPageChange: true,
				displayItemsByPageSelector: true,
				itemsByPageText: 'Items by page',
				pageText: 'Page',
				nbItemsByPageOptions: [5, 10, 15, 20, 30, 60, 100],
				headerElement: null,
				noDataText: '<div>No data to display</div>',
				onDataUpdate: function(data) {}
			}
		};
		
		params = $.extend($.universalPaginate.defaults, options || {});
		
		return this.each(function() {
			var list = this,
				$pagesList = $('<div class="' + params.universalPaginateClass + '_pages"></div>'),
				$header,
				itemTemplate,
				refreshTimer = null,
				additionalData = null,
				
				// Creates pagination links
				createPagination = function(nbPages, pageId) {
					var prevPageId = pageId - 1,
						nextPageId = pageId + 1;
					
					$pagesList.empty();
					for (var i = 1; i <= nbPages; i++) {
						if ((i == 1 || i == nbPages) || (nbPages > params.nbPagesDisplayed && i > pageId-3 && i < pageId+3) || nbPages <= params.nbPagesDisplayed) {
							$pagesList.append($('<a href="#" class="' + params.universalPaginateClass + '_link ' + params.universalPaginateClass + '_page_' + i + (i==pageId?' active':'') + '">' + i + '</a>').data(params.universalPaginateClass + '.page_id', i));
						}
						else if (nbPages > params.nbPagesDisplayed && (i == pageId-3 || i == pageId+3)) {
							$pagesList.append('<span class="' + params.universalPaginateClass + '_dots">...</span>');
						}
					}
					
					// Add the navigation arrows if needed
					if (params.paginationNavigationArrows === true) {
						if (prevPageId >= 1) {
							$pagesList.prepend($('<a href="#" class="' + params.universalPaginateClass + '_link ' + params.universalPaginateClass + '_page_' + prevPageId + '">&lt;</a>').data(params.universalPaginateClass + '.page_id', prevPageId));
						}
						if (nextPageId <= nbPages) {
							$pagesList.append($('<a href="#" class="' + params.universalPaginateClass + '_link ' + params.universalPaginateClass + '_page_' + nextPageId + '">&gt;</a>').data(params.universalPaginateClass + '.page_id', nextPageId));
						}
					}
					
					// Add the "page" text
					$pagesList.prepend(params.pageText);
				},
				
				// Changes the current page to pageId
				goToPage = function(pageId) {
					if (!additionalData) data = {};
					else data = additionalData;
					
					data.startIndex = (pageId - 1) * params.nbItemsByPage;
					data.nbItemsByPage = params.nbItemsByPage;
					
					// Get the data
					$.getJSON(params.dataUrl, data, function(data) {
						var nbPages = Math.ceil(data.nbTotalItems / params.nbItemsByPage);
						
						if (data.nbTotalItems == 0) {
							$(list).empty().append(params.noDataText);
						}
						else {
							// Create content
							$.tmpl(itemTemplate, data.data).appendTo($(list).empty());
						}
						
						// Create pagination links
						createPagination(nbPages, pageId);
						
						// Update current page
						$(list).data(params.universalPaginateClass + '.currentPage', pageId);
						
						params.onDataUpdate(data);
					});
				},
				
				// Allows changing the number of items by page
				allowChangingItemsByPage = function() {
					$header.delegate('.' + params.universalPaginateClass + '_nb_items_by_page_selector', 'change', function() {
						params.nbItemsByPage = $(this).val();
						goToPage(1);
					});
				},
				
				// Displays the number of items by page selector
				displayItemsByPageSelector = function() {
					var $select = $('<select>'),
						$wrapper = $('<div>');
					
					$.each(params.nbItemsByPageOptions, function(i, val) {
						$select.append($('<option>', {
							value: val,
							text: val,
							selected: val==params.nbItemsByPage
						}));
					});
					
					$select.addClass(params.universalPaginateClass + '_nb_items_by_page_selector');
					
					$wrapper.addClass(params.universalPaginateClass + '_nb_items_by_page').append(params.itemsByPageText).append($select);
					
					// Create the pagination zone
					$header.prepend($wrapper);
				},
				
				// Refreshes list data
				refreshList = function(data, reInit) {
					if (!data) data = {};
					if (!reInit) reInit = false;
					
					if (reInit) {
						goToPage(1);
					}
					else { 
						data.startIndex = ($(list).data(params.universalPaginateClass + '.currentPage') - 1) * params.nbItemsByPage;
						data.nbItemsByPage = params.nbItemsByPage;
						
						$.getJSON(params.dataUrl, 
							data, 
							function(newData) {
								var nbPages = Math.ceil(newData.nbTotalItems / params.nbItemsByPage);
								
								if (newData.nbTotalItems == 0) {
									$(list).empty().append(params.noDataText);
								}
								else {
									// Create content
									$.tmpl(itemTemplate, newData.data).appendTo($(list).empty());
								}
								
								// Re-create pagination links
								createPagination(nbPages, $(list).data(params.universalPaginateClass + '.currentPage'));
								
								params.onDataUpdate(newData);
							}
						);
					}
				},
				
				// Inits the refresh cycle
				initRefreshTimer = function(interval) {
					if (refreshTimer) {
						clearInterval(refreshTimer);
					}
					if (interval > 0) {
						refreshTimer = setInterval(function () {
							refreshList(additionalData);
						}, interval);
					}
				};
			
			// Create the item template
			// If the template is given as a string
			if (typeof params.itemTemplate == 'string') {
				itemTemplate = $.template(params.itemTemplate);
			}
			// Assume it is a jQuery object
			else {
				itemTemplate = $(params.itemTemplate).template();
			}
			
			// Create the header
			if (params.headerElement) {
				$header = $(params.headerElement);
			}
			else {
				$header = $('<div class="' + params.universalPaginateClass + '_header"><div class="clear"></div></div>');
				if (params.controlsPosition === 'top') {
					$(list).before($header);
				}
				else {
					$(list).after($header);
				}
			}
			$header.prepend($pagesList);
			
			// Allow changing the number of items by page
			if (params.allowItemsByPageChange === true) {
				allowChangingItemsByPage();
			}
			if (params.displayItemsByPageSelector === true) {
				displayItemsByPageSelector();
			}
			
			// Bind the pagination links
			$pagesList.delegate('.' + params.universalPaginateClass + '_link', 'click', function() {
				goToPage($(this).data(params.universalPaginateClass + '.page_id'));
				return false;
			});
			
			// Init the refresh cycle, if needed
			if (params.refreshInterval) {
				initRefreshTimer(params.refreshInterval);
			}
			
			// Bind custom event to allow on-demand data refreshing
			$(list).bind(params.universalPaginateClass + '.refresh', function(ev, data) {
				additionalData = data;
				refreshList(data);
			});
			$(list).bind(params.universalPaginateClass + '.full_refresh', function(ev, data) {
				additionalData = data;
				refreshList(data, true);
			});
			
			// Generate the first page
			goToPage(1);
		});
	};

})(jQuery);