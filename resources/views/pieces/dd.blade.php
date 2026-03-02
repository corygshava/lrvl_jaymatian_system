<style>
	/* Styles for the dd() function */

	/* Main container */
	.dd-container {
		font-family: 'Menlo', 'Monaco', 'Courier New', monospace;
		font-size: 14px;
		line-height: 1.6;
		background: #f8f9fa;
		padding: 20px;
		min-height: 100vh;
		box-sizing: border-box;
	}

	/* Individual dump block */
	.dd-block {
		background: #ffffff;
		border: 1px solid #dee2e6;
		border-radius: 8px;
		margin-bottom: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		overflow: hidden;
	}

	/* Header for multiple arguments */
	.dd-header {
		background: #ff2d20;
		color: #ffffff;
		padding: 12px 16px;
		font-weight: 600;
		font-size: 13px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		border-bottom: 1px solid #dee2e6;
	}

	/* Content area */
	.dd-content {
		padding: 16px;
		margin: 0;
		overflow-x: auto;
		white-space: pre;
		color: #2d3748;
	}

	/* Syntax highlighting colors */
	.dd-null {
		color: #e53e3e;
		font-weight: 600;
	}

	.dd-undefined {
		color: #718096;
		font-style: italic;
	}

	.dd-boolean {
		color: #d69e2e;
		font-weight: 600;
	}

	.dd-number {
		color: #3182ce;
		font-weight: 600;
	}

	.dd-string {
		color: #38a169;
	}

	.dd-function {
		color: #805ad5;
		font-style: italic;
	}

	.dd-date {
		color: #dd6b20;
		font-weight: 600;
	}

	.dd-key {
		color: #2d3748;
		font-weight: 600;
	}

	.dd-bracket,
	.dd-brace {
		color: #4a5568;
		font-weight: bold;
	}

	.dd-constructor {
		color: #d53f8c;
		font-weight: 600;
		font-style: italic;
	}

	.dd-circular {
		color: #e53e3e;
		font-weight: 600;
		font-style: italic;
	}

	/* Scrollbar styling (optional) */
	.dd-content::-webkit-scrollbar {
		height: 8px;
	}

	.dd-content::-webkit-scrollbar-track {
		background: #f1f1f1;
		border-radius: 4px;
	}

	.dd-content::-webkit-scrollbar-thumb {
		background: #cbd5e0;
		border-radius: 4px;
	}

	.dd-content::-webkit-scrollbar-thumb:hover {
		background: #a0aec0;
	}
</style>
<script>
	/**
	 * Laravel-style dd() function for JavaScript
	 * Dumps data with syntax highlighting
	 */

	function dd(...args) {
		const container = document.createElement('div');
		container.className = 'dd-container';

		// Add each argument as a separate dump
		args.forEach((data, index) => {
			const dumpBlock = document.createElement('div');
			dumpBlock.className = 'dd-block';

			// Add a header if multiple arguments
			if (args.length > 1) {
				const header = document.createElement('div');
				header.className = 'dd-header';
				header.textContent = `Argument ${index + 1}`;
				dumpBlock.appendChild(header);
			}

			// Create the content area
			const content = document.createElement('pre');
			content.className = 'dd-content';
			content.innerHTML = formatValue(data, 0);

			dumpBlock.appendChild(content);
			container.appendChild(dumpBlock);
		});

		return `<div class="dd-container">${container.innerHTML}</div>`;
		// kills exec by making an error happen
		// throw new Error('dd() - Execution stopped');
	}

	function formatValue(value, depth = 0, visited = new WeakSet()) {
		const indent = '  '.repeat(depth);
		const nextIndent = '  '.repeat(depth + 1);

		// Handle null
		if (value === null) {
			return `<span class="dd-null">null</span>`;
		}

		// Handle undefined
		if (value === undefined) {
			return `<span class="dd-undefined">undefined</span>`;
		}

		// Handle boolean
		if (typeof value === 'boolean') {
			return `<span class="dd-boolean">${value}</span>`;
		}

		// Handle number
		if (typeof value === 'number') {
			return `<span class="dd-number">${value}</span>`;
		}

		// Handle string
		if (typeof value === 'string') {
			return `<span class="dd-string">"${escapeHtml(value)}"</span>`;
		}

		// Handle function
		if (typeof value === 'function') {
			return `<span class="dd-function">${escapeHtml(value.toString())}</span>`;
		}

		// Handle Date
		if (value instanceof Date) {
			return `<span class="dd-date">${value.toISOString()}</span>`;
		}

		// Handle circular references
		if (typeof value === 'object' && visited.has(value)) {
			return `<span class="dd-circular">*CIRCULAR*</span>`;
		}

		// Handle arrays
		if (Array.isArray(value)) {
			if (value.length === 0) {
				return `<span class="dd-bracket">[]</span>`;
			}

			visited.add(value);
			const items = value.map((item, index) => {
				return `${nextIndent}<span class="dd-key">${index}</span> => ${formatValue(item, depth + 1, visited)}`;
			}).join(',\n');

			return `<span class="dd-bracket">[</span>\n${items}\n${indent}<span class="dd-bracket">]</span>`;
		}

		// Handle objects
		if (typeof value === 'object') {
			const keys = Object.keys(value);
			if (keys.length === 0) {
				return `<span class="dd-brace">{}</span>`;
			}

			visited.add(value);
			const constructor = value.constructor.name !== 'Object' ? `${value.constructor.name} ` : '';
			const items = keys.map(key => {
				return `${nextIndent}<span class="dd-key">"${escapeHtml(key)}"</span>: ${formatValue(value[key], depth + 1, visited)}`;
			}).join(',\n');

			return `<span class="dd-constructor">${constructor}</span><span class="dd-brace">{</span>\n${items}\n${indent}<span class="dd-brace">}</span>`;
		}

		return String(value);
	}

	function escapeHtml(str) {
		const div = document.createElement('div');
		div.textContent = str;
		return div.innerHTML;
	}
</script>
