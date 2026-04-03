<x-layouts.mainlayout>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #1a1f2e;
            --primary-blue: #2563eb;
            --primary-blue-hover: #1d4ed8;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-orange: #f59e0b;
            --neutral-100: #f8f9fa;
            --neutral-200: #e9ecef;
            --neutral-300: #dee2e6;
            --neutral-700: #495057;
            --neutral-800: #343a40;
            --border-color: #d1d5db;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Public Sans', sans-serif;
            /*background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);*/
            background: #eee;
            min-height: 100vh;
            padding: 2rem 1rem;
            color: var(--neutral-800);
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 900px;
        }

        .main-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2d3748 100%);
            color: white;
            padding: 2rem;
            border-bottom: 3px solid var(--primary-blue);
        }

        .card-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header h1 i {
            color: var(--primary-blue);
            font-size: 2rem;
        }

        .card-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .card-body {
            padding: 2rem;
        }

        .section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--neutral-700);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title::before {
            content: '';
            width: 3px;
            height: 14px;
            background: var(--primary-blue);
            border-radius: 2px;
        }

        .type-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .type-btn {
            position: relative;
            padding: 24px 16px;
            border: 2px solid var(--border-color);
            background: var(--neutral-100);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .type-btn:hover {
            border-color: var(--primary-blue);
            background: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .type-btn.active {
            border-color: var(--primary-blue);
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: var(--shadow-md);
        }

        .type-btn i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            color: var(--neutral-700);
            transition: color 0.3s ease;
        }

        .type-btn.active i {
            color: var(--primary-blue);
        }

        .type-btn span {
            display: block;
            font-weight: 600;
            color: var(--neutral-800);
        }

        .format-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .format-btn {
            padding: 1rem;
            border: 2px solid var(--border-color);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .format-btn:hover {
            border-color: var(--primary-blue);
            transform: translateX(4px);
        }

        .format-btn.active {
            border-color: var(--primary-blue);
            background: var(--primary-blue);
            color: white;
        }

        .format-btn i {
            font-size: 1.5rem;
        }

        .format-btn span {
            font-weight: 500;
        }

        .upload-zone {
            border: 3px dashed var(--border-color);
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            background: var(--neutral-100);
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 2rem;
        }

        .upload-zone:hover {
            border-color: var(--primary-blue);
            background: white;
        }

        .upload-zone.dragover {
            border-color: var(--primary-blue);
            background: #eff6ff;
            border-style: solid;
        }

        .upload-zone i {
            font-size: 3rem;
            color: var(--neutral-700);
            margin-bottom: 1rem;
        }

        .upload-zone p {
            margin: 0;
            color: var(--neutral-700);
        }

        .upload-zone .btn-upload {
            margin-top: 1rem;
        }

        .preview-section {
            background: var(--neutral-100);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: none;
        }

        .preview-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .preview-container {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            max-height: 400px;
            overflow: auto;
            border: 1px solid var(--border-color);
        }

        .preview-container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 4px;
        }

        .file-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }

        .file-info-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .file-info-left i {
            font-size: 1.5rem;
            color: var(--primary-blue);
        }

        .file-name {
            font-weight: 500;
            color: var(--neutral-800);
        }

        .file-size {
            font-size: 0.85rem;
            color: var(--neutral-700);
            font-family: 'IBM Plex Mono', monospace;
        }

        .btn-remove {
            background: none;
            border: none;
            color: var(--accent-red);
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.25rem 0.5rem;
            transition: transform 0.2s ease;
        }

        .btn-remove:hover {
            transform: scale(1.2);
        }

        .form-group label {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            font-family: 'IBM Plex Mono', monospace;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .metadata-section {
            background: var(--neutral-100);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: none;
        }

        .metadata-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .progress-section {
            display: none;
            margin-bottom: 2rem;
        }

        .progress-section.active {
            display: block;
        }

        .progress {
            height: 8px;
            border-radius: 8px;
            background: var(--neutral-200);
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-green));
            transition: width 0.3s ease;
            border-radius: 8px;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-family: 'IBM Plex Mono', monospace;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: var(--primary-blue-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:disabled {
            background: var(--neutral-300);
            cursor: not-allowed;
            transform: none;
        }

        .btn-upload {
            background: white;
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-upload:hover {
            background: var(--primary-blue);
            color: white;
        }

        .results-section {
            display: none;
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            border-left: 4px solid var(--primary-blue);
            box-shadow: var(--shadow-lg);
        }

        .results-section.active {
            display: block;
            animation: slideUp 0.4s ease-out;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--neutral-200);
        }

        .result-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .result-icon.success {
            background: #d1fae5;
            color: var(--accent-green);
        }

        .result-icon.error {
            background: #fee2e2;
            color: var(--accent-red);
        }

        .result-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .result-data {
            background: var(--neutral-100);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .result-data pre {
            margin: 0;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.85rem;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .result-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--neutral-700);
            margin-bottom: 0.5rem;
        }

        .hidden {
            display: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .type-selector,
            .format-selector {
                grid-template-columns: 1fr;
            }

            .card-body {
                padding: 1.5rem;
            }

            .upload-zone {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card">
            <div class="card-header">
                <h1>
                    <!-- <i class="fas fa-shield-alt"></i> -->
                    File Verification
                </h1>
                <p>Upload and verify your reports and invoices</p>
            </div>

            <div class="card-body">
                <!-- Document Type Selection -->
                <div class="section-title">
                    <span>Document Type</span>
                </div>

                <div class="type-selector">
                    <div class="type-btn" data-type="report">
                        <i class="fas fa-file-alt"></i>
                        <span>Report</span>
                    </div>
                    <div class="type-btn" data-type="invoice">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Invoice</span>
                    </div>
                </div>

                <!-- File Format Selection -->
                <div class="section-title">
                    <span>File Format</span>
                </div>

                <div class="format-selector">
                    <div class="format-btn" data-format="image">
                        <i class="fas fa-image"></i>
                        <span>Image (JPG, PNG)</span>
                    </div>
                    <div class="format-btn" data-format="pdf">
                        <i class="fas fa-file-pdf"></i>
                        <span>PDF Document</span>
                    </div>
                </div>

                <!-- Upload Zone -->
                <div class="section-title">
                    <span>Upload File</span>
                </div>

                <div class="upload-zone" id="uploadZone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag and drop your file here</p>
                    <p style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">or</p>
                    <button class="btn-upload" onclick="document.getElementById('fileInput').click()">
                        Browse Files
                    </button>
                    <input type="file" id="fileInput" class="hidden" accept="image/*,.pdf">
                </div>

                <!-- File Info -->
                <div id="fileInfoSection" class="hidden">
                    <div class="file-info">
                        <div class="file-info-left">
                            <i class="fas fa-file" id="fileIcon"></i>
                            <div>
                                <div class="file-name" id="fileName"></div>
                                <div class="file-size" id="fileSize"></div>
                            </div>
                        </div>
                        <button class="btn-remove" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Image Preview -->
                <div class="preview-section" id="previewSection">
                    <div class="section-title">
                        <span>Image Preview</span>
                    </div>
                    <div class="preview-container">
                        <img id="imagePreview" src="" alt="File preview">
                    </div>
                </div>

                <!-- Metadata Section -->
                <div class="metadata-section" id="metadataSection">
                    <div class="section-title">
                        <span>Document Information</span>
                    </div>

                    <!-- Invoice Fields -->
                    <div id="invoiceFields" class="hidden">
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" class="form-control" id="invoiceNo" placeholder="INV-2024-001">
                        </div>
                        <div class="form-group">
                            <label>Date of Issue</label>
                            <input type="date" class="form-control" id="invoiceDateOfIssue">
                        </div>
                    </div>

                    <!-- Report Fields -->
                    <div id="reportFields" class="hidden">
                        <div class="form-group">
                            <label>Job Number</label>
                            <input type="text" class="form-control" id="jobNo" placeholder="JOB-2024-001">
                        </div>
                        <div class="form-group">
                            <label>Date of Issue</label>
                            <input type="date" class="form-control" id="reportDateOfIssue">
                        </div>
                        <div class="form-group">
                            <label>Number of Samples</label>
                            <input type="number" class="form-control" id="numberOfSamples" placeholder="5">
                        </div>
                        <div class="form-group">
                            <label>Attendant Name</label>
                            <input type="text" class="form-control" id="attendantName" placeholder="John Doe">
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="progress-section" id="progressSection">
                    <div class="progress-info">
                        <span>Uploading...</span>
                        <span id="progressPercent">0%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button class="btn-primary" id="submitBtn" onclick="submitVerification()" disabled>
                    <i class="fas fa-check-circle"></i>
                    Verify Document
                </button>

                <!-- Results Section -->
                <div class="results-section" id="resultsSection">
                    <div class="result-header">
                        <div class="result-icon" id="resultIcon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h3 class="result-title" id="resultTitle">Verification Complete</h3>
                        </div>
                    </div>

                    <div id="resultContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // State management
        let state = {
            documentType: null,
            fileFormat: null,
            selectedFile: null
        };

        // Document type selection
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                state.documentType = this.dataset.type;
                updateMetadataSection();
                validateForm();
            });
        });

        // File format selection
        document.querySelectorAll('.format-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.format-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                state.fileFormat = this.dataset.format;
                updateFileInputAccept();
                validateForm();
            });
        });

        // Update file input accept attribute
        function updateFileInputAccept() {
            const fileInput = document.getElementById('fileInput');
            if (state.fileFormat === 'image') {
                fileInput.accept = 'image/*';
            } else if (state.fileFormat === 'pdf') {
                fileInput.accept = '.pdf,application/pdf';
            }
        }

        // File input handling
        document.getElementById('fileInput').addEventListener('change', function(e) {
            handleFileSelection(e.target.files[0]);
        });

        // Drag and drop
        const uploadZone = document.getElementById('uploadZone');

        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            handleFileSelection(e.dataTransfer.files[0]);
        });

        // Handle file selection
        function handleFileSelection(file) {
            if (!file) return;

            // Validate file type
            if (state.fileFormat === 'image' && !file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }

            if (state.fileFormat === 'pdf' && file.type !== 'application/pdf') {
                alert('Please select a PDF file');
                return;
            }

            state.selectedFile = file;

            // Show file info
            document.getElementById('fileInfoSection').classList.remove('hidden');
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);

            // Update icon
            const fileIcon = document.getElementById('fileIcon');
            if (state.fileFormat === 'image') {
                fileIcon.className = 'fas fa-image';
            } else {
                fileIcon.className = 'fas fa-file-pdf';
            }

            // Show preview for images
            if (state.fileFormat === 'image') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('previewSection').classList.add('active');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewSection').classList.remove('active');
            }

            validateForm();
        }

        // Remove file
        function removeFile() {
            state.selectedFile = null;
            document.getElementById('fileInput').value = '';
            document.getElementById('fileInfoSection').classList.add('hidden');
            document.getElementById('previewSection').classList.remove('active');
            validateForm();
        }

        // Update metadata section based on document type
        function updateMetadataSection() {
            const metadataSection = document.getElementById('metadataSection');
            const invoiceFields = document.getElementById('invoiceFields');
            const reportFields = document.getElementById('reportFields');

            if (state.documentType) {
                metadataSection.classList.add('active');

                if (state.documentType === 'invoice') {
                    invoiceFields.classList.remove('hidden');
                    reportFields.classList.add('hidden');
                } else {
                    reportFields.classList.remove('hidden');
                    invoiceFields.classList.add('hidden');
                }
            } else {
                metadataSection.classList.remove('active');
            }
        }

        // Validate form
        function validateForm() {
            const submitBtn = document.getElementById('submitBtn');
            let isValid = state.documentType && state.fileFormat && state.selectedFile;

            if (isValid && state.documentType === 'invoice') {
                const invoiceNo = document.getElementById('invoiceNo').value.trim();
                const dateOfIssue = document.getElementById('invoiceDateOfIssue').value;
                isValid = invoiceNo && dateOfIssue;
            }

            if (isValid && state.documentType === 'report') {
                const jobNo = document.getElementById('jobNo').value.trim();
                const dateOfIssue = document.getElementById('reportDateOfIssue').value;
                const numberOfSamples = document.getElementById('numberOfSamples').value;
                const attendantName = document.getElementById('attendantName').value.trim();
                isValid = jobNo && dateOfIssue && numberOfSamples && attendantName;
            }

            submitBtn.disabled = !isValid;
        }

        // Add input listeners for validation
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', validateForm);
            input.addEventListener('change', validateForm);
        });

        // Submit verification
        function submitVerification() {
            if (!state.selectedFile) return;

            const formData = new FormData();
            formData.append('file', state.selectedFile);
            formData.append('document_type', state.documentType);
            formData.append('file_format', state.fileFormat);

            if (state.documentType === 'invoice') {
                formData.append('invoice_no', document.getElementById('invoiceNo').value);
                formData.append('date_of_issue', document.getElementById('invoiceDateOfIssue').value);
            } else {
                formData.append('job_no', document.getElementById('jobNo').value);
                formData.append('date_of_issue', document.getElementById('reportDateOfIssue').value);
                formData.append('number_of_samples', document.getElementById('numberOfSamples').value);
                formData.append('attendant_name', document.getElementById('attendantName').value);
            }

            // Show progress section
            document.getElementById('progressSection').classList.add('active');
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('resultsSection').classList.remove('active');

            // XHR request
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    document.getElementById('progressBar').style.width = percentComplete + '%';
                    document.getElementById('progressPercent').textContent = percentComplete + '%';
                }
            });

            xhr.addEventListener('load', function() {
                document.getElementById('progressSection').classList.remove('active');
                document.getElementById('submitBtn').disabled = false;

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        displayResults(response);
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Error parsing server response');
                    }
                } else {
                    alert('Upload failed with status: ' + xhr.status);
                }
            });

            xhr.addEventListener('error', function() {
                document.getElementById('progressSection').classList.remove('active');
                document.getElementById('submitBtn').disabled = false;
                alert('Upload failed. Please try again.');
            });

            xhr.open('POST', './../data/verify_files', true);
            xhr.send(formData);
        }

        // Display results
        function displayResults(response) {
            const resultsSection = document.getElementById('resultsSection');
            const resultIcon = document.getElementById('resultIcon');
            const resultTitle = document.getElementById('resultTitle');
            const resultContent = document.getElementById('resultContent');

            resultsSection.classList.add('active');

            // Check authorization
            if (!response.success) {
                resultIcon.classList.remove('success');
                resultIcon.classList.add('error');
                resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                resultTitle.textContent = 'Unauthorized';
                resultContent.innerHTML = '<p style="color: var(--accent-red);">You are not authorized to perform this action.</p>';
                return;
            }

            // Check verification result
            if (response.result) {
                resultIcon.classList.remove('error');
                resultIcon.classList.add('success');
                resultIcon.innerHTML = '<i class="fas fa-check"></i>';
                resultTitle.textContent = 'File Verified Successfully';
            } else {
                resultIcon.classList.remove('success');
                resultIcon.classList.add('error');
                resultIcon.innerHTML = '<i class="fas fa-times"></i>';
                resultTitle.textContent = 'Verification Failed';
            }

            // Build result content
            let html = '';

            // Display tosend data
            if (response.tosend) {
                html += '<div class="result-data">';
                html += '<div class="result-label">Saved Data</div>';
                html += '<pre>' + JSON.stringify(response.tosend, null, 2) + '</pre>';
                html += '</div>';
            }

            // Display runlog
            if (response.runlog && response.runlog.length > 0) {
                html += '<div class="result-data">';
                html += '<div class="result-label">Run Log</div>';
                html += '<pre>' + response.runlog.join('\n') + '</pre>';
                html += '</div>';
            }

            // Display xtra data
            if (response.xtra && response.xtra.length > 0) {
                html += '<div class="result-data">';
                html += '<div class="result-label">Additional Data</div>';
                html += '<pre>' + JSON.stringify(response.xtra, null, 2) + '</pre>';
                html += '</div>';
            }

            resultContent.innerHTML = html;
        }

        // Utility function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>
</x-layouts.mainlayout>
