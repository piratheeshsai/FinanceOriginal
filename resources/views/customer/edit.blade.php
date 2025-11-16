@extends('layouts.app')

@section('breadcrumb')
    Customer
@endsection

@section('page-title')
    Update Customer
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
/* Traditional Bank-Style Customer Creation Form - Important Styles */

/* Root Variables */
:root {
    --primary-color: #1a365d;
    --primary-light: #2d4a6d;
    --secondary-color: #4a5568;
    --success-color: #22543d;
    --danger-color: #c53030;
    --warning-color: #744210;
    --border-color: #d1d5db;
    --border-light: #e5e7eb;
    --bg-light: #f9fafb;
    --bg-white: #ffffff;
    --text-primary: #1a202c;
    --text-secondary: #4a5568;
    --text-muted: #718096;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --radius: 6px;
    --radius-lg: 8px;
    --transition: all 0.2s ease;
}

/* Base Styles */
* {
    box-sizing: border-box !important;
}



body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif !important;
    background-color: var(--bg-light) !important;
    color: var(--text-primary) !important;
    line-height: 1.6 !important;
    min-height: 2000px !important;
}

/* Header Section */
.form-header {
    background: var(--bg-white) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: var(--radius-lg) !important;
    padding: 2rem !important;
    margin-bottom: 1.5rem !important;
    box-shadow: var(--shadow-sm) !important;
}

.header-content {
    margin-bottom: 1.5rem !important;
}

.page-title {
    font-size: 1.875rem !important;
    font-weight: 700 !important;
    color: var(--primary-color) !important;
    margin: 0 0 0.5rem 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
}

.page-title i {
    color: var(--primary-color) !important;
}

.page-subtitle {
    color: var(--text-secondary) !important;
    margin: 0 !important;
    font-size: 1rem !important;
}

.progress-section {
    border-top: 1px solid var(--border-light) !important;
    padding-top: 1.5rem !important;
}

.progress-info {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 0.75rem !important;
}

.progress-label {
    font-weight: 600 !important;
    color: var(--text-primary) !important;
}

.progress-value {
    color: var(--text-secondary) !important;
    font-weight: 500 !important;
}

.progress-bar-container {
    height: 8px !important;
    background-color: var(--border-light) !important;
    border-radius: 4px !important;
    overflow: hidden !important;
}

.progress-bar {
    height: 100% !important;
    background-color: var(--primary-color) !important;
    transition: width 0.3s ease !important;
    border-radius: 4px !important;
}

/* Step Navigation */
.step-navigation {
    display: flex !important;
    background: var(--bg-white) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: var(--radius-lg) !important;
    padding: 1rem !important;
    margin-bottom: 1.5rem !important;
    box-shadow: var(--shadow-sm) !important;
    position: relative !important;
}

.step-navigation::before {
    content: '' !important;
    position: absolute !important;
    top: 50% !important;
    left: 5% !important;
    right: 5% !important;
    height: 2px !important;
    background-color: var(--border-color) !important;
    z-index: 1 !important;
}

.nav-step {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-align: center !important;
    position: relative !important;
    z-index: 2 !important;
    padding: 0 1rem !important;
}

.nav-step-number {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50% !important;
    background: var(--bg-light) !important;
    border: 2px solid var(--border-color) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-weight: 700 !important;
    color: var(--text-muted) !important;
    margin-bottom: 0.5rem !important;
    transition: var(--transition) !important;
}

.nav-step-label {
    font-size: 0.875rem !important;
    color: var(--text-muted) !important;
    font-weight: 500 !important;
    transition: var(--transition) !important;
}

.nav-step.active .nav-step-number {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: white !important;
}

.nav-step.active .nav-step-label {
    color: var(--primary-color) !important;
    font-weight: 600 !important;
}

.nav-step.completed .nav-step-number {
    background: var(--success-color) !important;
    border-color: var(--success-color) !important;
    color: white !important;
}

.nav-step.completed .nav-step-label {
    color: var(--success-color) !important;
}

/* Form Container */
.form-container {
    background: var(--bg-white) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow) !important;
    overflow: hidden !important;
}

/* Form Steps */
.form-step {
    display: none !important;
    padding: 2rem !important;
}

.form-step.active {
    display: block !important;
    animation: fadeIn 0.3s ease !important;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Step Header */
.step-header {
    margin-bottom: 2rem !important;
    padding-bottom: 1rem !important;
    border-bottom: 2px solid var(--border-light) !important;
}

.step-title {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
    color: var(--primary-color) !important;
    margin: 0 0 0.5rem 0 !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
}

.step-icon {
    width: 40px !important;
    height: 40px !important;
    background: var(--primary-color) !important;
    color: white !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 1.125rem !important;
}

.step-description {
    color: var(--text-secondary) !important;
    margin: 0 !important;
}

/* Form Grid */
.form-grid {
    max-width: none !important;
}

.form-row {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    gap: 1.5rem !important;
    margin-bottom: 1.5rem !important;
}

.form-group {
    display: flex !important;
    flex-direction: column !important;
}

.form-group.full-width {
    grid-column: 1 / -1 !important;
}

.form-group.address-group {
    grid-column: 1 / -1 !important;
}

/* Form Labels */
.form-label {
    display: block !important;
    font-weight: 600 !important;
    color: var(--text-primary) !important;
    margin-bottom: 0.5rem !important;
    font-size: 0.875rem !important;
}

.required {
    color: var(--danger-color) !important;
}

/* Form Inputs */
.form-input, .form-textarea, .form-select {
    width: 100% !important;
    padding: 0.75rem 0.75rem 0.75rem 2.75rem !important; /* Add left padding for icon */
    border: 2px solid var(--border-color) !important;
    border-radius: var(--radius) !important;
    font-size: 1rem !important;
    color: var(--text-primary) !important;
    background: var(--bg-white) !important;
    transition: var(--transition) !important;
    box-sizing: border-box !important;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none !important;
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1) !important;
}

.form-input {
    padding-left: 2.75rem !important;
}

.input-icon {
    position: absolute !important;
    left: 1rem !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: var(--text-muted) !important;
    font-size: 1rem !important;
    pointer-events: none !important;
    transition: var(--transition) !important;
    z-index: 5 !important;
}

/* Hide icons for inputs with prefix */
.input-group .input-icon {
    display: none !important;
}

/* Fix Select Styling */
.form-select {
    padding: 0.75rem !important; /* No icon padding for selects */
    appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
    background-position: right 0.5rem center !important;
    background-repeat: no-repeat !important;
    background-size: 1.5em 1.5em !important;
    padding-right: 2.5rem !important;
}

/* Fix Input with Prefix */
.input-group {
    display: flex !important;
    align-items: stretch !important;
    width: 100% !important;
}

.input-prefix {
    background: var(--bg-light) !important;
    border: 2px solid var(--border-color) !important;
    border-right: none !important;
    padding: 0.75rem 1rem !important;
    font-weight: 600 !important;
    color: var(--text-secondary) !important;
    border-radius: var(--radius) 0 0 var(--radius) !important;
    display: flex !important;
    align-items: center !important;
    white-space: nowrap !important;
}

.form-input.with-prefix {
    border-left: none !important;
    border-radius: 0 var(--radius) var(--radius) 0 !important;
    padding-left: 0.75rem !important; /* Remove icon padding for prefix inputs */
}

/* Address Input Group */
.address-input-group {
    position: relative !important;
}

.copy-address-btn {
    position: absolute !important;
    top: 0.75rem !important;
    right: 0.75rem !important;
    width: 32px !important;
    height: 32px !important;
    background: var(--bg-light) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: var(--radius) !important;
    color: var(--text-secondary) !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: var(--transition) !important;
    z-index: 10 !important;
}

.copy-address-btn:hover {
    background: var(--primary-color) !important;
    color: white !important;
    border-color: var(--primary-color) !important;
}

/* City Input Group */
.city-input-group {
    display: flex !important;
    gap: 0.5rem !important;
}

.city-input-group .form-select {
    flex: 1 !important;
}

.add-city-btn {
    width: 44px !important;
    height: 44px !important;
    background: var(--bg-light) !important;
    border: 2px solid var(--border-color) !important;
    border-radius: var(--radius) !important;
    color: var(--text-secondary) !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: var(--transition) !important;
    font-weight: 600 !important;
}

.add-city-btn:hover {
    background: var(--primary-color) !important;
    color: white !important;
    border-color: var(--primary-color) !important;
    text-decoration: none !important;
}

/* Form Sections */
.form-section {
    margin: 2rem 0 !important;
    padding: 1.5rem !important;
    background: var(--bg-light) !important;
    border: 1px solid var(--border-light) !important;
    border-radius: var(--radius) !important;
}

.section-title {
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    color: var(--primary-color) !important;
    margin: 0 0 1rem 0 !important;
}

/* Radio Groups */
.radio-group {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important;
    gap: 1rem !important;
}

.radio-option {
    display: flex !important;
    align-items: center !important;
    cursor: pointer !important;
    padding: 0.75rem !important;
    border: 2px solid var(--border-color) !important;
    border-radius: var(--radius) !important;
    background: var(--bg-white) !important;
    transition: var(--transition) !important;
}

.radio-option:hover {
    border-color: var(--primary-light) !important;
    background: rgba(26, 54, 93, 0.02) !important;
}

.radio-option input[type="radio"] {
    display: none !important;
}

.radio-checkmark {
    width: 20px !important;
    height: 20px !important;
    border: 2px solid var(--border-color) !important;
    border-radius: 50% !important;
    margin-right: 0.75rem !important;
    position: relative !important;
    transition: var(--transition) !important;
}

.radio-checkmark::after {
    content: '' !important;
    width: 10px !important;
    height: 10px !important;
    border-radius: 50% !important;
    background: var(--primary-color) !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) scale(0) !important;
    transition: var(--transition) !important;
}

.radio-option input[type="radio"]:checked + .radio-checkmark {
    border-color: var(--primary-color) !important;
}

.radio-option input[type="radio"]:checked + .radio-checkmark::after {
    transform: translate(-50%, -50%) scale(1) !important;
}

.radio-option input[type="radio"]:checked ~ .radio-label {
    color: var(--primary-color) !important;
    font-weight: 600 !important;
}

.radio-label {
    font-weight: 500 !important;
    color: var(--text-primary) !important;
    transition: var(--transition) !important;
}

/* Checkbox Groups */
.checkbox-group {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
    gap: 1rem !important;
}

.checkbox-option {
    display: flex !important;
    align-items: center !important;
    cursor: pointer !important;
    padding: 0.75rem !important;
    border: 2px solid var(--border-color) !important;
    border-radius: var(--radius) !important;
    background: var(--bg-white) !important;
    transition: var(--transition) !important;
}

.checkbox-option:hover {
    border-color: var(--primary-light) !important;
    background: rgba(26, 54, 93, 0.02) !important;
}

.checkbox-option input[type="checkbox"] {
    display: none !important;
}

.checkbox-checkmark {
    width: 20px !important;
    height: 20px !important;
    border: 2px solid var(--border-color) !important;
    border-radius: 4px !important;
    margin-right: 0.75rem !important;
    position: relative !important;
    transition: var(--transition) !important;
}

.checkbox-checkmark::after {
    content: '✓' !important;
    color: white !important;
    font-size: 14px !important;
    font-weight: bold !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) scale(0) !important;
    transition: var(--transition) !important;
}

.checkbox-option input[type="checkbox"]:checked + .checkbox-checkmark {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

.checkbox-option input[type="checkbox"]:checked + .checkbox-checkmark::after {
    transform: translate(-50%, -50%) scale(1) !important;
}

.checkbox-option input[type="checkbox"]:checked ~ .checkbox-label {
    color: var(--primary-color) !important;
    font-weight: 600 !important;
}

.checkbox-label {
    font-weight: 500 !important;
    color: var(--text-primary) !important;
    transition: var(--transition) !important;
}

/* File Upload */
.file-upload-area {
    border: 2px dashed var(--border-color) !important;
    border-radius: var(--radius) !important;
    padding: 2rem !important;
    text-align: center !important;
    background: var(--bg-light) !important;
    transition: var(--transition) !important;
    cursor: pointer !important;
    position: relative !important;
}

.file-upload-area:hover {
    border-color: var(--primary-color) !important;
    background: rgba(26, 54, 93, 0.02) !important;
}

.file-upload-area.drag-over {
    border-color: var(--primary-color) !important;
    background: rgba(26, 54, 93, 0.05) !important;
}

.file-upload-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
}

.file-icon {
    font-size: 3rem !important;
    color: var(--text-muted) !important;
    margin-bottom: 1rem !important;
}

.file-title {
    font-weight: 600 !important;
    color: var(--text-primary) !important;
    margin: 0 0 0.5rem 0 !important;
}

.file-subtitle {
    color: var(--text-secondary) !important;
    margin: 0 0 0.5rem 0 !important;
}

.file-info {
    color: var(--text-muted) !important;
    font-size: 0.875rem !important;
    margin: 0 !important;
}

.file-input {
    position: absolute !important;
    inset: 0 !important;
    opacity: 0 !important;
    cursor: pointer !important;
}

.file-preview {
    margin-top: 1rem !important;
    padding-top: 1rem !important;
    border-top: 1px solid var(--border-light) !important;
}

.file-preview-item {
    display: flex !important;
    align-items: center !important;
    gap: 1rem !important;
    padding: 0.75rem !important;
    background: var(--bg-white) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: var(--radius) !important;
}

.file-preview-item img {
    width: 50px !important;
    height: 50px !important;
    object-fit: cover !important;
    border-radius: var(--radius) !important;
}

.file-preview-info {
    flex: 1 !important;
}

.file-name {
    font-weight: 600 !important;
    color: var(--text-primary) !important;
    display: block !important;
}

.file-size {
    color: var(--text-muted) !important;
    font-size: 0.875rem !important;
}

.file-remove {
    width: 32px !important;
    height: 32px !important;
    background: var(--danger-color) !important;
    color: white !important;
    border: none !important;
    border-radius: 50% !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: var(--transition) !important;
}

.file-remove:hover {
    background: #e53e3e !important;
}

/* Form Navigation */
.form-navigation {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 1.5rem 2rem !important;
    background: var(--bg-light) !important;
    border-top: 1px solid var(--border-color) !important;
}

.btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 0.75rem 1.5rem !important;
    border: 2px solid !important;
    border-radius: var(--radius) !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    cursor: pointer !important;
    transition: var(--transition) !important;
    font-size: 1rem !important;
}

.btn-secondary {
    background: var(--bg-white) !important;
    border-color: var(--border-color) !important;
    color: var(--text-secondary) !important;
}

.btn-secondary:hover {
    background: var(--secondary-color) !important;
    border-color: var(--secondary-color) !important;
    color: white !important;
}

.btn-primary {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: white !important;
}

.btn-primary:hover {
    background: var(--primary-light) !important;
    border-color: var(--primary-light) !important;
}

.btn-success {
    background: var(--success-color) !important;
    border-color: var(--success-color) !important;
    color: white !important;
}

.step-info {
    text-align: center !important;
    color: var(--text-secondary) !important;
    font-weight: 500 !important;
}

.step-separator {
    margin: 0 0.5rem !important;
}

/* Validation States */
.is-invalid {
    border-color: var(--danger-color) !important;
    box-shadow: 0 0 0 3px rgba(197, 48, 48, 0.1) !important;
    animation: shake 0.4s ease !important;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-header {
        padding: 1.5rem !important;
    }

    .page-title {
        font-size: 1.5rem !important;
    }

    .step-navigation {
        flex-direction: column !important;
        gap: 1rem !important;
        padding: 1.5rem !important;
    }

    .step-navigation::before {
        display: none !important;
    }

    .nav-step {
        flex-direction: row !important;
        justify-content: center !important;
    }

    .nav-step-number {
        margin-bottom: 0 !important;
        margin-right: 0.75rem !important;
    }

    .form-step {
        padding: 1.5rem !important;
    }

    .form-row {
        grid-template-columns: 1fr !important;
    }

    .radio-group,
    .checkbox-group {
        grid-template-columns: 1fr !important;
    }

    .form-navigation {
        flex-direction: column !important;
        gap: 1rem !important;
        padding: 1.5rem !important;
    }

    .btn {
        width: 100% !important;
        justify-content: center !important;
    }

    .step-info {
        order: -1 !important;
    }

    .city-input-group {
        flex-direction: column !important;
    }
}

@media (max-width: 480px) {
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .form-header {
        padding: 1rem !important;
    }

    .form-step {
        padding: 1rem !important;
    }

    .file-upload-area {
        padding: 1.5rem 1rem !important;
    }
}

.input-container {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
}
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header Section -->
            <div class="form-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-user-edit"></i>
                        Customer Details Update Form
                    </h2>
                    <p class="page-subtitle">Please fill out all required information accurately</p>
                </div>
                <div class="progress-section">
                    <div class="progress-info">
                        <span class="progress-label">Form Progress</span>
                        <span class="progress-value" id="progress-text">Step 1 of 4</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" id="main-progress" style="width: 25%"></div>
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <div class="nav-step active" data-step="1">
                    <div class="nav-step-number">1</div>
                    <div class="nav-step-label">Basic Info</div>
                </div>
                <div class="nav-step" data-step="2">
                    <div class="nav-step-number">2</div>
                    <div class="nav-step-label">Address & Type</div>
                </div>
                <div class="nav-step" data-step="3">
                    <div class="nav-step-number">3</div>
                    <div class="nav-step-label">Spouse Details</div>
                </div>
                <div class="nav-step" data-step="4">
                    <div class="nav-step-number">4</div>
                    <div class="nav-step-label">Family Info</div>
                </div>
                <div class="nav-step" data-step="5">
                    <div class="nav-step-number">5</div>
                    <div class="nav-step-label">Documents</div>
                </div>
            </div>

            <!-- Main Form -->
            <div class="form-container">
                <form id="signUpForm" action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Step 1: Basic Personal Information -->
                    <div class="form-step active" id="step-1">
                        <div class="step-header">
                            <h3 class="step-title">
                                <span class="step-icon"><i class="fas fa-user"></i></span>
                                Basic Personal Information
                            </h3>
                            <p class="step-description">Please provide customer basic details</p>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Full Name <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="name" name="full_name" class="form-input" type="text"
                                               value="{{ old('full_name', $customer->full_name) }}" placeholder="Enter your full name" required>
                                        <i class="input-icon fas fa-user"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">NIC Number <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="nic" name="nic" class="form-input" type="text"
                                               value="{{ old('nic', $customer->nic) }}" placeholder="Enter NIC number" required>
                                        <i class="input-icon fas fa-id-card"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phone Number <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="customer_phone" name="customer_phone" class="form-input" type="tel"
                                               value="{{ old('customer_phone', $customer->customer_phone) }}" placeholder="Enter phone number" required>
                                        <i class="input-icon fas fa-phone"></i>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label class="form-label">Date of Birth <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="date_of_birth" name="date_of_birth" class="form-input datepicker"
                                               type="text" value="{{ old('date_of_birth', $customer->date_of_birth) }}" placeholder="Select date of birth" required>
                                        <i class="input-icon fas fa-calendar"></i>
                                    </div>
                                </div>


                            </div>

                            <div class="form-row">

                                <div class="form-group">
                                    <label class="form-label">Gender <span class="required">*</span></label>
                                    <select class="form-select" name="gender" id="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $customer->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Occupation <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input name="occupation" class="form-input" type="text"
                                               value="{{ old('occupation', $customer->occupation) }}" placeholder="Enter your occupation" required>
                                        <i class="input-icon fas fa-briefcase"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- NEW ROW FOR CENTRE -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Centre <span class="required">*</span></label>
                                    <select id="centre_id" name="center_id" class="form-select" required>
                                        <option value="">Select Centre</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}" {{ old('center_id', $customer->center_id) == $center->id ? 'selected' : '' }}>
                                                {{ $center->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Address & Customer Type -->
                    <div class="form-step" id="step-2">
                        <div class="step-header">
                            <h3 class="step-title">
                                <span class="step-icon"><i class="fas fa-map-marker-alt"></i></span>
                                Address & Customer Type
                            </h3>
                            <p class="step-description">Please provide address details and customer type</p>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label class="form-label">Permanent Address <span class="required">*</span></label>
                                    <textarea id="permanent_address" name="permanent_address" class="form-textarea"
                                            rows="3" placeholder="Enter your permanent address" required>{{ old('permanent_address', $customer->permanent_address) }}</textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group address-group">
                                    <label class="form-label">Mailing Address <span class="required">*</span></label>
                                    <div class="address-input-group">
                                        <textarea id="living_address" name="living_address" class="form-textarea"
                                                  rows="3" placeholder="Enter your mailing address" required>{{ old('living_address', $customer->living_address) }}</textarea>
                                        <button type="button" class="copy-address-btn" id="copyAddressButton"
                                                title="Copy from permanent address">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Permanent City <span class="required">*</span></label>
                                    <select class="form-select" name="permanent_city" id="permanent_city" required>
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->name }}" {{ old('permanent_city', $customer->permanent_city) == $city->name ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Mailing City <span class="required">*</span></label>
                                    <div class="city-input-group">
                                        <select class="form-select" name="living_city" id="living_city" required>
                                            <option value="">Select City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->name }}" {{ old('living_city', $customer->living_city) == $city->name ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <a href="{{ route('settings.cities') }}" class="add-city-btn" title="Add New City">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h4 class="section-title">Civil Status <span class="required">*</span></h4>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="civil_status" value="single" onchange="toggleSpouseDetails()"
                                               {{ old('civil_status', $customer->civil_status) == 'single' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Single</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="civil_status" id="marriedCheckbox" value="married" onchange="toggleSpouseDetails()"
                                               {{ old('civil_status', $customer->civil_status) == 'married' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Married</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="civil_status" value="divorced" onchange="toggleSpouseDetails()"
                                               {{ old('civil_status', $customer->civil_status) == 'divorced' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Divorced</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="civil_status" value="widowed" onchange="toggleSpouseDetails()"
                                               {{ old('civil_status', $customer->civil_status) == 'widowed' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Widowed</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-section">
                                <h4 class="section-title">Customer Type <span class="required">*</span></h4>
                                <div class="checkbox-group">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="customer_types[]" value="1" id="customer"
                                               {{ in_array(1, old('customer_types', $customer->types->pluck('id')->toArray() )) ? 'checked' : '' }}>
                                        <span class="checkbox-checkmark"></span>
                                        <span class="checkbox-label">Customer</span>
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="customer_types[]" value="2" id="guarantor"
                                               {{ in_array(2, old('customer_types', $customer->types->pluck('id')->toArray() )) ? 'checked' : '' }}>
                                        <span class="checkbox-checkmark"></span>
                                        <span class="checkbox-label">Guarantor</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Spouse Details (update the ID) -->
                    <div class="form-step" id="step-3">
                        <div class="step-header">
                            <h3 class="step-title">
                                <span class="step-icon"><i class="fas fa-heart"></i></span>
                                Spouse Information
                            </h3>
                            <p class="step-description">Please provide your spouse's details</p>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Spouse Name <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="spouse_name" name="spouse_name" class="form-input" type="text"
                                               value="{{ old('spouse_name', $customer->spouse_name) }}" placeholder="Enter spouse name" required>
                                        <i class="input-icon fas fa-user"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Spouse NIC <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="spouse_nic" name="spouse_nic" class="form-input" type="text"
                                               value="{{ old('spouse_nic', $customer->spouse_nic) }}" placeholder="Enter spouse NIC" required>
                                        <i class="input-icon fas fa-id-card"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phone Number <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="phone_number" name="Spouse_phone" class="form-input" type="tel"
                                               placeholder="Enter phone number" maxlength="11" required>
                                        <i class="input-icon fas fa-phone"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Spouse Occupation <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input name="spouse_occupation" class="form-input" type="text"
                                               placeholder="Enter spouse occupation" required>
                                        <i class="input-icon fas fa-briefcase"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Spouse Age <span class="required">*</span></label>
                                    <div class="input-container">
                                        <input id="spouse_age" name="spouse_age" class="form-input" type="number"
                                               placeholder="Enter spouse age" required>
                                        <i class="input-icon fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Family Details (update the ID) -->
                    <div class="form-step" id="step-4">
                        <div class="step-header">
                            <h3 class="step-title">
                                <span class="step-icon"><i class="fas fa-home"></i></span>
                                Family Information
                            </h3>
                            <p class="step-description">Please provide family and household details (Optional)</p>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Number of Family Members</label>
                                    <div class="input-container">
                                        <input id="family_members" name="family_members" class="form-input" type="number"
                                               value="{{ old('family_members', $customer->family_members) }}" placeholder="Enter number of family members">
                                        <i class="input-icon fas fa-users"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Income Earners</label>
                                    <div class="input-container">
                                        <input id="income_earners" name="income_earners" class="form-input" type="number"
                                               value="{{ old('income_earners', $customer->income_earners) }}" placeholder="Enter number of income earners">
                                        <i class="input-icon fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Family Income</label>
                                    <div class="input-container">
                                        <input id="family_income" name="family_income" class="form-input" type="number"
                                               value="{{ old('family_income', $customer->family_income) }}" placeholder="Enter family income">
                                        <i class="input-icon fas fa-money-bill-wave"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Home Phone</label>
                                    <div class="input-container">
                                        <input id="home_phone" name="home_phone" class="form-input" type="tel"
                                               value="{{ old('home_phone', $customer->home_phone) }}" placeholder="Enter home phone">
                                        <i class="input-icon fas fa-phone"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Documents (update the ID) -->
                    <div class="form-step" id="step-5">
                        <div class="step-header">
                            <h3 class="step-title">
                                <span class="step-icon"><i class="fas fa-file-alt"></i></span>
                                Document Upload
                            </h3>
                            <p class="step-description">Please upload documents (Optional)</p>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Customer Photo</label>
                                    <div class="file-upload-area" data-file-type="image">
                                        <div class="file-upload-content">
                                            <i class="fas fa-camera file-icon"></i>
                                            <div class="file-text">
                                                <p class="file-title">Upload Customer Photo</p>
                                                <p class="file-subtitle">Click to browse or drag and drop</p>
                                                <p class="file-info">Supported: JPG, PNG (Max 5MB)</p>
                                            </div>
                                        </div>
                                        <input type="file" name="photo" class="file-input" accept="image/*">
                                        <div class="file-preview"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">NIC Copy</label>
                                    <div class="file-upload-area" data-file-type="document">
                                        <div class="file-upload-content">
                                            <i class="fas fa-id-card file-icon"></i>
                                            <div class="file-text">
                                                <p class="file-title">Upload NIC Document</p>
                                                <p class="file-subtitle">Click to browse or drag and drop</p>
                                                <p class="file-info">Supported: JPG, PNG, PDF (Max 5MB)</p>
                                            </div>
                                        </div>
                                        <input type="file" name="nic_copy" class="file-input" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="file-preview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Navigation -->
                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev(-1)">
                            <i class="fas fa-arrow-left"></i>
                            Previous
                        </button>

                        <div class="step-info">
                            <span id="current-step">Step 1</span>
                            <span class="step-separator">of</span>
                            <span class="total-steps">4</span>
                        </div>

                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">
                            Next
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Traditional Customer Creation Form JavaScript
let currentTab = 0;
const totalSteps = 5; // Updated total steps

// Initialize form when page loads
document.addEventListener('DOMContentLoaded', function() {
    showTab(currentTab);
    initializeFormFeatures();
});

function showTab(n) {
    const steps = document.querySelectorAll('.form-step');
    steps.forEach(step => step.classList.remove('active'));
    if (steps[n]) steps[n].classList.add('active');
    updateNavigation(n);
    updateProgress(n);
    updateStepIndicators(n);
    // Remove the setTimeout and scroll call from here
}

function updateNavigation(n) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // Previous button
    prevBtn.style.display = n === 0 ? 'none' : 'inline-flex';

    // Next/Submit button
    if (n === totalSteps - 1) {
        nextBtn.innerHTML = '<i class="fas fa-check"></i> Submit';
        nextBtn.classList.remove('btn-primary');
        nextBtn.classList.add('btn-success');
    } else {
        nextBtn.innerHTML = 'Next <i class="fas fa-arrow-right"></i>';
        nextBtn.classList.remove('btn-success');
        nextBtn.classList.add('btn-primary');
    }
}

function updateProgress(n) {
    const progress = ((n + 1) / totalSteps) * 100;
    const progressBar = document.getElementById('main-progress');
    const progressText = document.getElementById('progress-text');
    const currentStep = document.getElementById('current-step');

    if (progressBar) {
        progressBar.style.width = progress + '%';
    }

    if (progressText) {
        progressText.textContent = `Step ${n + 1} of ${totalSteps}`;
    }

    if (currentStep) {
        currentStep.textContent = `Step ${n + 1}`;
    }
}

function updateStepIndicators(n) {
    const navSteps = document.querySelectorAll('.nav-step');

    navSteps.forEach((step, index) => {
        step.classList.remove('active', 'completed');

        if (index < n) {
            step.classList.add('completed');
        } else if (index === n) {
            step.classList.add('active');
        }
    });
}

function nextPrev(direction) {
    console.log('nextPrev called with direction:', direction);

    // Validate current step before proceeding
    if (direction === 1 && !validateCurrentStep()) {
        return false;
    }

    // Check step skipping logic
    const nextTab = calculateNextStep(direction);

    // Boundary checks
    if (nextTab >= totalSteps) {
        submitForm();
        return false;
    }

    if (nextTab < 0) {
        return false;
    }

    // Update current tab and show
    currentTab = nextTab;
    showTab(currentTab);

    // SCROLL TO TOP - USE MULTIPLE METHODS
    console.log('Scrolling to top...');

    // Method 1: Force immediate scroll
    window.scrollTo(0, 0);

    // Method 2: Use document methods
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;

    // Method 3: Scroll to the form header element
    const formHeader = document.querySelector('.form-header');
    if (formHeader) {
        formHeader.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    // Method 4: Use requestAnimationFrame
    requestAnimationFrame(() => {
        window.scrollTo(0, 0);
    });
}

function calculateNextStep(direction) {
    const marriedCheckbox = document.getElementById('marriedCheckbox');
    const guarantorCheckbox = document.getElementById('guarantor');
    const customerCheckbox = document.getElementById('customer');

    const isMarried = marriedCheckbox ? marriedCheckbox.checked : false;
    const isGuarantor = guarantorCheckbox ? guarantorCheckbox.checked : false;
    const isCustomer = customerCheckbox ? customerCheckbox.checked : false;

    let nextTab = currentTab + direction;

    // Skip spouse step if not married
    if (direction === 1 && nextTab === 2 && !isMarried) {
        nextTab = 3; // Skip to family details
    } else if (direction === -1 && currentTab === 3 && !isMarried) {
        nextTab = 1; // Skip back to address & type
    }

    // Skip family step if only guarantor
    if (isGuarantor && !isCustomer) {
        if (direction === 1 && (nextTab === 2 || nextTab === 3)) {
            nextTab = 4; // Skip to documents
        } else if (direction === -1 && currentTab === 4) {
            nextTab = 1; // Skip back to address & type
        }
    }

    return nextTab;
}

function validateCurrentStep() {
    const currentStep = document.querySelectorAll('.form-step')[currentTab];
    if (!currentStep) return false;

    // Skip validation for family details step (step 3)
    if (currentTab === 3) {
        return true;
    }

    const requiredFields = currentStep.querySelectorAll('[required]');
    let isValid = true;

    // Clear previous validation states
    currentStep.querySelectorAll('.is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
    });

    // Validate each required field
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    // Special validation for radio groups
    const radioGroups = getRadioGroups(currentStep);
    radioGroups.forEach(group => {
        if (!validateRadioGroup(group)) {
            isValid = false;
        }
    });

    // Special validation for checkbox groups (customer type) - FIX: Check step 1, not 0
    if (currentTab === 1) { // Changed from 0 to 1
        if (!validateCustomerType()) {
            isValid = false;
        }
    }

    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;

    if (field.type === 'file') {
        isValid = field.files.length > 0;
    } else if (field.type === 'radio') {
        // Radio validation handled separately
        return true;
    } else if (field.type === 'checkbox') {
        // Checkbox validation handled separately
        return true;
    } else {
        isValid = value !== '';
    }

    if (!isValid) {
        field.classList.add('is-invalid');
    }

    return isValid;
}

function getRadioGroups(step) {
    const radioInputs = step.querySelectorAll('input[type="radio"]');
    const groups = {};

    radioInputs.forEach(radio => {
        if (!groups[radio.name]) {
            groups[radio.name] = [];
        }
        groups[radio.name].push(radio);
    });

    return Object.values(groups);
}

function validateRadioGroup(group) {
    const isChecked = group.some(radio => radio.checked);

    if (!isChecked) {
        group.forEach(radio => {
            radio.closest('.radio-option').classList.add('is-invalid');
        });
        return false;
    }

    return true;
}

function validateCustomerType() {
    const customerCheckbox = document.getElementById('customer');
    const guarantorCheckbox = document.getElementById('guarantor');

    const isValid = customerCheckbox.checked || guarantorCheckbox.checked;

    if (!isValid) {
        customerCheckbox.closest('.checkbox-option').classList.add('is-invalid');
        guarantorCheckbox.closest('.checkbox-option').classList.add('is-invalid');
    }

    return isValid;
}

function submitForm() {
    const form = document.getElementById('signUpForm');

    // Show loading state
    const submitBtn = document.getElementById('nextBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    submitBtn.disabled = true;

    // Submit form
    setTimeout(() => {
        form.submit();
    }, 500);
}

function initializeFormFeatures() {
    initializeAddressCopy();
    initializeNICValidation();
    initializeDatePicker();
    initializeFileUploads();
}

function initializeAddressCopy() {
    const copyBtn = document.getElementById('copyAddressButton');

    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const permanentAddress = document.getElementById('permanent_address').value;
            const permanentCity = document.getElementById('permanent_city').value;

            document.getElementById('living_address').value = permanentAddress;
            document.getElementById('living_city').value = permanentCity;

            // Visual feedback
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy"></i>';
            }, 1000);
        });
    }
}

function initializeNICValidation() {
    const nicPattern = /^\d{0,15}[a-zA-Z]?$/;

    function validateNIC(event) {
        const input = event.target;
        const value = input.value;

        if (!nicPattern.test(value)) {
            input.value = value.slice(0, -1);
        }
    }

    const nicInputs = document.querySelectorAll('#nic, #spouse_nic');
    nicInputs.forEach(input => {
        input.addEventListener('input', validateNIC);
    });
}

function initializeDatePicker() {
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.datepicker', {
            dateFormat: 'Y-m-d',
            maxDate: 'today',
            disableMobile: true,
            theme: 'light'
        });
    }
}

function initializeFileUploads() {
    const fileInputs = document.querySelectorAll('.file-input');

    fileInputs.forEach(input => {
        const uploadArea = input.closest('.file-upload-area');
        const preview = uploadArea.querySelector('.file-preview');
        const uploadContent = uploadArea.querySelector('.file-upload-content');

        // Click handler for upload area - only on the area itself, not the entire container
        uploadContent.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            input.click();
        });

        // File change handler
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                handleFileSelect(file, uploadArea, preview, input);
            }
        });

        // Drag and drop handlers
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('drag-over');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                input.files = files;
                handleFileSelect(file, uploadArea, preview, input);
            }
        });
    });
}


function handleFileSelect(file, uploadArea, preview, input) {
    if (!file) return;

    // File size validation (5MB limit)
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    if (file.size > maxSize) {
        alert('File size must be less than 5MB');
        input.value = '';
        return;
    }

    // File type validation
    const fileType = uploadArea.getAttribute('data-file-type');
    if (fileType === 'image') {
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file (JPG, PNG)');
            input.value = '';
            return;
        }
    } else if (fileType === 'document') {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid file (JPG, PNG, PDF)');
            input.value = '';
            return;
        }
    }

    // Clear previous preview
    preview.innerHTML = '';

    // Create preview
    const previewItem = document.createElement('div');
    previewItem.className = 'file-preview-item';

    // File icon/preview
    const fileIcon = document.createElement('div');
    fileIcon.className = 'file-icon-preview';

    if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.width = '50px';
        img.style.height = '50px';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '6px';
        fileIcon.appendChild(img);
    } else if (file.type === 'application/pdf') {
        fileIcon.innerHTML = '<i class="fas fa-file-pdf" style="font-size: 2rem; color: #e53e3e;"></i>';
    } else {
        fileIcon.innerHTML = '<i class="fas fa-file" style="font-size: 2rem; color: #6b7280;"></i>';
    }

    // File info
    const info = document.createElement('div');
    info.className = 'file-preview-info';
    info.innerHTML = `
        <span class="file-name">${file.name}</span>
        <span class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
    `;

    // Remove button
    const removeBtn = document.createElement('button');
    removeBtn.className = 'file-remove';
    removeBtn.type = 'button';
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.title = 'Remove file';

    // Fixed remove functionality
    removeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        // Clear the input value
        input.value = '';

        // Clear the preview
        preview.innerHTML = '';

        // Reset the upload area to default state
        uploadArea.classList.remove('drag-over');

        console.log('File removed successfully');
    });

    // Assemble preview item
    previewItem.appendChild(fileIcon);
    previewItem.appendChild(info);
    previewItem.appendChild(removeBtn);
    preview.appendChild(previewItem);

    // Hide the upload content when file is selected
    const uploadContent = uploadArea.querySelector('.file-upload-content');
    uploadContent.style.display = 'none';
}

function toggleSpouseDetails() {
    // This function can be extended for additional spouse-related logic
    console.log('Spouse details toggled');
}

// Form validation feedback
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('is-invalid')) {
        e.target.classList.remove('is-invalid');
    }
});

// Remove validation classes from radio/checkbox options
document.addEventListener('change', function(e) {
    if (e.target.type === 'radio' || e.target.type === 'checkbox') {
        const option = e.target.closest('.radio-option, .checkbox-option');
        if (option) {
            option.classList.remove('is-invalid');
        }
    }
});




</script>

@endsection
