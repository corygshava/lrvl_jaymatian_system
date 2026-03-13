<style>
	/* stats (keep here, ill figure them out later) */
		.stat-card-modern {
			background: white;
			border-radius: var(--radius);
			padding: 20px;
			display: flex;
			align-items: center;
			gap: 15px;
			box-shadow: var(--shadow-sm);
			transition: all 0.3s;
			border: 1px solid var(--border);
		}
		.stat-card-modern:hover {
			transform: translateY(-3px);
			box-shadow: var(--shadow);
		}
		.stat-icon-modern {
			width: 50px;
			height: 50px;
			border-radius: var(--radius-sm);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 20px;
		}

		.stat-icon-modern.total { background: var(--primary-light); color: var(--primary); }
		.stat-icon-modern.active { background: #d1fae5; color: var(--success); }
		.stat-icon-modern.inactive { background: #fee2e2; color: var(--danger); }
		.stat-icon-modern.new { background: #fef3c7; color: var(--warning); }

		.stat-info-modern h3 {
			font-size: 24px;
			font-weight: 700;
			margin: 0;
			color: var(--dark);
		}
		.stat-info-modern p {
			margin: 0;
			color: var(--gray);
			font-size: 13px;
		}

	/* Filter Bar */
		.filter-bar {
			background: white;
			border-radius: var(--roundness);
			padding: 20px;
			margin-bottom: 20px;
			/*box-shadow: var(--shadow-sm);*/
			/*border: 1px solid var(--border);*/
		}
		.filter-row {
			display: flex;
			gap: 15px;
			flex-wrap: wrap;
			align-items: end;
		}
		.filter-group {
			flex: 1;
			min-width: 200px;
		}
		.filter-group label {
			display: block;
			font-size: 12px;
			font-weight: 600;
			color: var(--gray);
			margin-bottom: 6px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
		.filter-control {
			width: 100%;
			padding: 10px 15px;
			border: 1px solid var(--clr-border);
			border-radius: var(--roundness);
			font-size: 14px;
			transition: all 0.3s;
			background: white;
		}
		.filter-control:focus {
			outline: none;
			border-color: var(--primary);
			box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
		}

	/* Data Container */
		.data-container {
			background: white;
			border-radius: var(--roundness);
			box-shadow: var(--shadow-sm);
			/*border: 1px solid var(--light-grey);*/
			overflow: hidden;
		}
		.data-header {
			padding: 20px;
			border-bottom: 1px solid var(--clr-border);
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 15px;
		}
		.data-title {
			font-size: 16px;
			font-weight: 600;
			color: var(--dark);
		}
		.data-actions {
			display: flex;
			gap: 10px;
			align-items: center;
		}

	/* Action Buttons */
		.action-btns {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			gap: 8px;
		}

		.btn-action {
			border-radius: 6px;
			border: none;
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			transition: all 0.3s;
			font-size: 13px;
			padding: 8px 12px;
			gap: 9px;
		}

		.btn-view { background: #e0e7ff; color: var(--primary); }
		.btn-edit { background: #fef3c7; color: var(--text); }
		.btn-delete { background: #fee2e2; color: var(--danger); }

		.btn-action:hover {
			transform: scale(1.1);
		}

	/* Pagination */
		.pagination-container {
			padding: 20px;
			border-top: 1px solid var(--border);
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 15px;
		}

		.pagination-info {
			font-size: 14px;
			color: var(--gray);
		}

		.pagination {
			display: flex;
			gap: 5px;
			margin: 0;
			padding: 0;
			list-style: none;
		}

		.page-item {
			list-style: none;
		}

		.page-link {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 36px;
			height: 36px;
			border-radius: 8px;
			border: 1px solid var(--border);
			background: white;
			color: var(--gray);
			font-size: 14px;
			cursor: pointer;
			transition: all 0.3s;
			text-decoration: none;
		}

		.page-link:hover, .page-link.active {
			background: var(--primary);
			color: white;
			border-color: var(--primary);
		}

		.page-link:disabled {
			opacity: 0.5;
			cursor: not-allowed;
		}

	/* Loading Spinner */
		.loading-overlay {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(255, 255, 255, 0.9);
			display: none;
			align-items: center;
			justify-content: center;
			z-index: 10;
		}

		.loading-overlay.show {
			display: flex;
		}

		.spinner {
			width: 50px;
			height: 50px;
			border: 4px solid var(--border);
			border-top-color: var(--primary);
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			to { transform: rotate(360deg); }
		}

	/* Empty State */
		.empty-state {
			text-align: center;
			padding: 60px 20px;
			display: none;
		}
		.empty-state.show {
			display: block;
		}
		.empty-icon {
			width: 80px;
			height: 80px;
			background: var(--primary-light);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 20px;
			color: var(--primary);
			font-size: 32px;
		}
		.empty-state h3 {
			font-size: 18px;
			color: var(--dark);
			margin-bottom: 10px;
		}
		.empty-state p {
			color: var(--gray);
			font-size: 14px;
		}

	/* Toast Notification */
		.toast-container {
			position: fixed;
			top: 20px;
			right: 20px;
			z-index: 3000;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}
		.toast_ {
			background: white;
			border-radius: var(--radius-sm);
			padding: 16px 20px;
			box-shadow: var(--shadow-lg);
			display: flex;
			align-items: center;
			gap: 12px;
			min-width: 300px;
			border-left: 4px solid;
		}
		.toast.success { border-left-color: var(--success); }
		.toast.error { border-left-color: var(--danger); }
		.toast.info { border-left-color: var(--info); }
		.toast-icon {
			width: 24px;
			height: 24px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 12px;
		}
		.toast.success .toast-icon { background: #d1fae5; color: var(--success); }
		.toast.error .toast-icon { background: #fee2e2; color: var(--danger); }
		.toast.info .toast-icon { background: #dbeafe; color: var(--info); }
		.toast-content {
			flex: 1;
		}
		.toast-title {
			font-weight: 600;
			font-size: 14px;
			color: var(--dark);
		}
		.toast-message {
			font-size: 13px;
			color: var(--gray);
			margin-top: 2px;
		}
		.toast-close {
			background: none;
			border: none;
			color: var(--gray);
			cursor: pointer;
			padding: 4px;
		}

	/* Responsive */
		@media (max-width: 768px) {
			.filter-row {
				flex-direction: column;
			}

			.filter-group {
				min-width: 100%;
			}

			.search-table input {
				width: 100%;
			}
		}
</style>

<div class="toast-container" id="toastContainer"></div>

<div class="container-fluid">
	<div class="page-header">
		<div class="page-title">
			<span class="h4">Reports management</span>
			<p>Here you can view and download generated reports</p>
		</div>
		<div class="header-actions">
			<button class="mybtn secondary" onclick="refreshData()">
				<i class="fas fa-sync-alt"></i>
				Refresh
			</button>
			<!-- <button class="mybtn primary" data-runme="add_record"> -->
			<button class="mybtn primary w3-hide" id="add_record_btn" data-runme="load_page" data-label="User management">
				<i class="fas fa-plus"></i>
				Add New task
			</button>
		</div>
	</div>
</div>
