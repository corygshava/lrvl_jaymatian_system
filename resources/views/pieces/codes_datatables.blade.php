<script>
	/**
	 * LaravelDataTable - Reusable Data Table Component
	 *
	 * @description A plug-and-play data table system for Laravel backends
	 * @author Cornelius Shava
	 *
	 * assumes you are using my template for sending info from APIs
	 *
	 */

	window['LaravelDataTable'] = (function() {
		// Private variables
		let config = {};
		let currentPage = 1;
		let perPage = 10;
		let totalRecords = 0;
		let currentFilters = {};
		let searchQuery = '';
		let sortColumn = '';
		let sortDirection = 'desc';
		let debounceTimer = null;

		/**
		 * Default configuration
		 */
		const defaultConfig = {
			// Required
			apiEndpoint: '/api/data',           // Laravel API endpoint
			tableBodyId: 'tableBody',           // ID of tbody element

			// Optional
			tableHeadId: 'tableHead',           // ID of thead element
			paginationId: 'pagination',         // ID of pagination container
			searchInputId: 'quickSearch',       // ID of search input
			loadingOverlayId: 'loadingOverlay', // ID of loading overlay
			emptyStateId: 'emptyState',         // ID of empty state container

			// Table configuration
			columns: [],                        // Array of column definitions
			perPage: 10,                        // Records per page (max 300)
			enableSearch: true,                 // Enable search functionality
			enableSort: true,                   // Enable column sorting
			enableFilters: false,               // Enable filters
			filters: [],                        // Array of filter definitions

			// Stats configuration
			enableStats: false,                 // Show stats cards
			statsEndpoint: null,                // Endpoint for stats data
			statsContainer: 'statsContainer',   // ID of stats container

			// Callbacks
			onRowClick: null,                   // Callback for row click
			onDataLoaded: null,                 // Callback after data loads
			onError: null,                      // Callback on error

			// Row rendering
			rowRenderer: null,                  // Custom row renderer function

			// CSRF token (Laravel)
			csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',

			// Additional request parameters
			additionalParams: {},
			sortDirection: 'desc',
		};

		/**
		 * Initialize the data table
		 * @param {Object} userConfig - User configuration
		 */
		function init(userConfig) {
			alert_info('loading information, please wait...');

			config = { ...defaultConfig, ...userConfig };
			perPage = Math.min(config.perPage, 300);
			sortDirection = config.sortDirection;

			// Validate required config
			if (!config.apiEndpoint) {
				console.error('LaravelDataTable: apiEndpoint is required');
				return;
			}

			if (!config.columns || config.columns.length === 0) {
				console.error('LaravelDataTable: columns configuration is required');
				return;
			}

			// Setup event listeners
			setupEventListeners();

			// Generate table headers
			generateTableHeaders();

			// Generate filters if enabled
			if (config.enableFilters && config.filters.length > 0) {
				generateFilters();
			}

			// Load stats if enabled
			if (config.enableStats && config.statsEndpoint) {
				loadStats();
			}

			// Load initial data
			loadData();
		}

		/**
		 * Setup event listeners
		 */
		function setupEventListeners() {
			const searchstuff = (e,immediate = false) => {
				clearTimeout(debounceTimer);

				let tosearch = e.target.value;

				if(tosearch == ''){
					alert_warning('type something first');
					return;
				}

				const searchit = () => {
					// alert_info(`searching for '${e.target.value}'`);
					searchQuery = e.target.value;
					currentPage = 1;
					loadData();
				}

				if(!immediate){
					debounceTimer = setTimeout(() => {
						searchit();
					}, 700); // debounce to prevent too many requests
				} else {
					searchit();
				}
			}

			// Search input
			if (config.enableSearch) {
				const searchInput = document.getElementById(config.searchInputId);
				if (searchInput) {
					searchInput.addEventListener('input', function(e) {
						searchstuff(e);
					});

					searchInput.addEventListener('keydown', function(e) {
						if(e.key.toLowerCase() == 'enter'){
							searchstuff(e,true);
						}
					});
				}
			}
		}

		/**
		 * Generate table headers
		 */
		function generateTableHeaders() {
			const thead = document.getElementById(config.tableHeadId);
			if (!thead) return;

			let headerHTML = '<tr>';

			config.columns.forEach(column => {
				const sortable = column.sortable !== false && config.enableSort;
				const sortClass = sortable ? 'cursor-pointer' : '';
				const sortIcon = sortable ? '<i class="fas fa-sort"></i>' : '';

				headerHTML += `
					<th class="${sortClass}"
						${sortable ? `onclick="sortbyme('${column.data}','${column.title}')"` : ''}
						${column.width ? `width="${column.width}"` : ''}>
						${column.title} ${sortIcon}
					</th>
				`;
			});

			headerHTML += '</tr>';
			thead.innerHTML = headerHTML;
		}

		/**
		 * Generate filters
		 */
		function generateFilters() {
			// /*
				const filterContainer = document.getElementById('filterContainer');
				if (!filterContainer) return;

				filterContainer.classList.add('w3-display-container');

				let reseter = filterContainer.querySelector('[data-subrole="reset"]');

				if(reseter == undefined){
					let r = document.createElement('div');
					r.dataset.subrole = "reset";
					r.className = "w3-display-topright spacy-sm";
					r.innerHTML = `
						<button class="btn btn-outline-secondary btn-sm themeround" data-runme="killfilters"><i class="fa fa-filter"></i> clear filters</button>
					`;

					filterContainer.appendChild(r);
				}

				const filterRow = filterContainer.querySelector('.filter-row');
				if (!filterRow) return;

				let filtersHTML = '';

				config.filters.forEach(filter => {
					let lbl = `${filter.label}`;
					filtersHTML += `
						<div class="filter-group" data-myobj="${JSON.stringify(filter)}">
							<label>${lbl}</label>
							<select class="filter-control" id="filter_${filter.name}"
									onchange="filterme('${filter.name}', this.value)">
								<option value="">All</option>
								${filter.options.map(opt =>
									`<option value="${opt.value || null}">${opt.label || '??'}</option>`
								).join('')}
							</select>
						</div>
					`;
				});

				filterRow.innerHTML = filtersHTML;
			// */
		}

		/**
		 * Load data from Laravel backend
		 */
		function loadData() {
			showLoading(true);

			// Build request parameters
			const params = {
				page: currentPage,
				per_page: perPage,
				search: searchQuery,
				sort_by: sortColumn,
				sort_direction: sortDirection,
				...currentFilters,
				...config.additionalParams
			};

			// Make AJAX request
			fetch(`${config.apiEndpoint}?${new URLSearchParams(params)}`, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': config.csrfToken,
					'Accept': 'application/json'
				}
			})
			.then(response => {
				if (!response.ok) {
					throw new Error(`HTTP error! status: ${response.status}`);
				}
				return response.json();
			})
			.then(data => {
				handleDataResponse(data);
				showLoading(false);

				// Call onDataLoaded callback
				if (config.onDataLoaded && typeof config.onDataLoaded === 'function') {
					config.onDataLoaded(data.sendme);
				}

				refreshUI(300);
			})
			.catch(error => {
				console.error('LaravelDataTable Error:', error);
				showLoading(false);
				showToast('error', 'Error', 'Failed to load data');

				// Call onError callback
				if (config.onError && typeof config.onError === 'function') {
					config.onError(error);
				}
			});
		}

		/**
		 * Handle data response from Laravel
		 * Expected format: { data: [], total: 0, per_page: 10, current_page: 1 }
		 */
		function handleDataResponse(gotten) {
			if(!gotten.success){
				alert_danger('access denied!');
				return;
			}

			if(!gotten.result){
				alert_danger('there was an error processing your request',12);
				return;
			}

			const response = gotten.sendme;
			const data = response.data || [];
			totalRecords = response.total || 0;
			currentPage = response.current_page || 1;

			// Render table rows
			renderTable(data);

			// Update pagination
			updatePagination(response);

			// Show/hide empty state
			toggleEmptyState(data.length === 0);
		}

		/**
		 * Render table rows
		 */
		function renderTable(data) {
			const tbody = document.getElementById(config.tableBodyId);
			if (!tbody) return;

			if (data.length === 0) {
				tbody.innerHTML = '';
				return;
			}

			let rowsHTML = '';

			data.forEach((row, index) => {
				if (config.rowRenderer && typeof config.rowRenderer === 'function') {
					// Use custom row renderer
					rowsHTML += config.rowRenderer(row, index);
				} else {
					// Default row renderer
					rowsHTML += renderDefaultRow(row, index);
				}
			});

			tbody.innerHTML = rowsHTML;

			// Attach row click events if configured
			if (config.onRowClick && typeof config.onRowClick === 'function') {
				tbody.querySelectorAll('tr').forEach((tr, index) => {
					tr.style.cursor = 'pointer';
					// passes the row, id and event data
					tr.addEventListener('click', (c) => config.onRowClick(data[index], index, c));
				});
			}
		}

		/**
		 * Default row renderer
		 */
		function renderDefaultRow(row, index) {
			let rowHTML = '<tr>';

			config.columns.forEach(column => {
				let cellValue = getNestedValue(row, column.data);

				// Apply cell renderer if provided
				if (column.render && typeof column.render === 'function') {
					cellValue = column.render(cellValue, row, index);
				}

				rowHTML += `<td>${cellValue !== null && cellValue !== undefined ? cellValue : '-'}</td>`;
			});

			rowHTML += '</tr>';
			return rowHTML;
		}

		/**
		 * Get nested object value
		 */
		function getNestedValue(obj, path) {
			return path.split('.').reduce((prev, curr) => {
				return prev ? prev[curr] : null;
			}, obj);
		}

		/**
		 * Update pagination
		 */
		function updatePagination(response) {
			const lastPage = response.last_page || 1;
			const from = response.from || 0;
			const to = response.to || 0;

			// Update pagination info
			document.getElementById('showingStart').textContent = from;
			document.getElementById('showingEnd').textContent = to;
			document.getElementById('totalItems').textContent = totalRecords;

			// Generate pagination buttons
			const paginationContainer = document.getElementById(config.paginationId);
			if (!paginationContainer) return;

			let paginationHTML = '';

			// Previous button
			paginationHTML += `
				<li class="page-item">
					<button class="page-link"
							onclick="LaravelDataTable.changePage(${currentPage - 1})"
							${currentPage === 1 ? 'disabled' : ''}>
						<i class="fas fa-chevron-left"></i>
					</button>
				</li>
			`;

			// Page numbers (show 5 pages at a time)
			const startPage = Math.max(1, currentPage - 2);
			const endPage = Math.min(lastPage, currentPage + 2);

			for (let i = startPage; i <= endPage; i++) {
				paginationHTML += `
					<li class="page-item">
						<button class="page-link ${i === currentPage ? 'active' : ''}"
								onclick="LaravelDataTable.changePage(${i})">
							${i}
						</button>
					</li>
				`;
			}

			// Next button
			paginationHTML += `
				<li class="page-item">
					<button class="page-link"
							onclick="LaravelDataTable.changePage(${currentPage + 1})"
							${currentPage === lastPage ? 'disabled' : ''}>
						<i class="fas fa-chevron-right"></i>
					</button>
				</li>
			`;

			paginationContainer.innerHTML = paginationHTML;
		}

		/**
		 * Change page
		 */
		function changePage(page) {
			currentPage = page;
			loadData();
		}

		/**
		 * Sort by column
		 */
		function sortBy(column,title) {
			// alert_info("chosen col: " + column);
			// return;

			title = title == undefined ? column : title;

			if (sortColumn === column) {
				sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
			} else {
				sortColumn = column;
				sortDirection = 'asc';
			}
			currentPage = 1;
			loadData();

			alert_info("sorting by: " + title);
		}

		/**
		 * Apply filter
		 */
		function applyFilter(filterName, value) {
			if (value === '') {
				delete currentFilters[filterName];
			} else {
				currentFilters[filterName] = value;
			}
			currentPage = 1;
			loadData();
		}

		/**
		 * Show/hide loading overlay
		 */
		function showLoading(show) {
			const overlay = document.getElementById(config.loadingOverlayId);
			if (overlay) {
				overlay.classList.toggle('show', show);
			}
		}

		/**
		 * Toggle empty state
		 */
		function toggleEmptyState(show) {
			const emptyState = document.getElementById(config.emptyStateId);
			if (emptyState) {
				emptyState.classList.toggle('show', show);
			}
		}

		/**
		 * Load stats data
		 */
		function loadStats() {
			fetch(config.statsEndpoint, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': config.csrfToken,
					'Accept': 'application/json'
				}
			})
			.then(response => response.json())
			.then(data => {
				renderStats(data);
			})
			.catch(error => {
				console.error('Stats loading error:', error);
			});
		}

		/**
		 * Render stats cards
		 */
		function renderStats(stats) {
			const container = document.getElementById(config.statsContainer);
			if (!container || !stats) return;

			let statsHTML = '';

			stats.forEach(stat => {
				statsHTML += `
					<div class="stat-card-modern">
						<div class="stat-icon-modern ${stat.type || 'total'}">
							<i class="${stat.icon || 'fas fa-chart-line'}"></i>
						</div>
						<div class="stat-info-modern">
							<h3>${stat.value}</h3>
							<p>${stat.label}</p>
						</div>
					</div>
				`;
			});

			container.innerHTML = statsHTML;
		}

		/**
		 * Refresh data
		 */
		function refresh() {
			loadData();
		}

		/**
		 * Reset filters
		 */
		function resetFilters() {
			currentFilters = {};
			searchQuery = '';
			currentPage = 1;

			// Reset filter inputs
			config.filters.forEach(filter => {
				const input = document.getElementById(`filter_${filter.name}`);
				if (input) input.value = '';
			});

			// Reset search input
			const searchInput = document.getElementById(config.searchInputId);
			if (searchInput) searchInput.value = '';

			loadData();
		}

		// Public API
		return {
			init: init,
			loadData: loadData,
			changePage: changePage,
			sortBy: sortBy,
			applyFilter: applyFilter,
			refresh: refresh,
			resetFilters: resetFilters,
			getConfig: () => config,
			getCurrentPage: () => currentPage,
			getTotalRecords: () => totalRecords
		};
	})();

	function sortbyme(who) {
		if(who !== undefined){
			console.log(LaravelDataTable);
			LaravelDataTable.sortBy(who);
		}
	}

	function filterme(w,wot) {
		LaravelDataTable.applyFilter(w,wot);
	}

	/**
	 * Refresh data helper
	 */
	window['refreshData'] = () => {
		LaravelDataTable.refresh();
		showToast('info', 'Refreshing', 'Loading latest data...');
	}

	// toast helper
	function showToast(type, title, message) {
		showAlert(message,3,type);
		return;

		const container = document.getElementById('toastContainer');
		if (!container) return;

		const toast = document.createElement('div');
		toast.className = `toast_ ${type} slide-l`;

		const icon = type === 'success' ? 'check' : type === 'error' ? 'exclamation' : 'info';

		toast.innerHTML = `
			<div class="toast-icon">
				<i class="fas fa-${icon}"></i>
			</div>
			<div class="toast-content">
				<div class="toast-title">${title}</div>
				<div class="toast-message">${message}</div>
			</div>
			<button class="toast-close" onclick="this.parentElement.remove()">
				<i class="fas fa-times"></i>
			</button>
		`;

		container.appendChild(toast);

		setTimeout(() => {
			toast.remove();
		}, 25000);
	}

	function killfilters() {
		alert_info('filters reset');
		LaravelDataTable.resetFilters();
	}
</script>
