@extends('layouts.app')

@section('title', 'Social Development Program Report')

@section('page-title', 'Social Development Program Report')

@push('styles')
<style>
    /* Report Page Container */
    .report-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header Section */
    .report-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .report-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 15px 0;
        text-align: center;
    }

    .report-header p {
        font-size: 15px;
        line-height: 1.8;
        margin: 0 0 10px 0;
        text-align: justify;
    }

    .report-header p:last-child {
        margin-bottom: 0;
        font-weight: 600;
        text-align: center;
        font-size: 16px;
    }

    /* Section Card */
    .section-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 3px solid #4e73df;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-subtitle {
        font-size: 13px;
        color: #7f8c8d;
        font-weight: 500;
        margin-top: 5px;
    }

    /* Table Styles */
    .activities-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .activities-table thead {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .activities-table thead th {
        color: white;
        padding: 15px 10px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .activities-table tbody td {
        padding: 12px 10px;
        border: 1px solid #e3e6f0;
        vertical-align: middle;
    }

    .activities-table tbody tr:hover {
        background: #f8f9fc;
    }

    /* Form Controls */
    .form-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d3e2;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d1d3e2;
        border-radius: 6px;
        font-size: 14px;
        background: white;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #4e73df;
    }

    /* Photo Upload */
    .photo-upload {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .photo-upload input[type="file"] {
        font-size: 13px;
    }

    .photo-limit-text {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
    }

    .photo-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .photo-preview img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #e3e6f0;
        cursor: pointer;
        transition: all 0.3s;
    }

    .photo-preview img:hover {
        border-color: #4e73df;
        transform: scale(1.05);
    }

    /* Action Buttons */
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #c92a1f 100%);
        color: white;
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-danger:hover {
        transform: scale(1.05);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Loading State */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ===============================
   ACTION ICON BUTTONS (TABLE)
    ================================ */
    .activities-table .btn {
        padding: 6px;
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        border-radius: 6px;
    }

    /* Icon size inside action buttons */
    .activities-table .btn i {
        font-size: 15px;
        line-height: 1;
    }

    /* Specific tweaks per action */
    .activities-table .btn-edit i {
        font-size: 15px;
    }

    .activities-table .btn-danger i {
        font-size: 15px;
    }

    .activities-table .btn-primary i {
        font-size: 15px;
    }

    .activities-table .btn-secondary i {
        font-size: 15px;
    }

    /* Hover feedback */
    .activities-table .btn:hover {
        transform: scale(1.08);
    }

    /* UPDATED: Only apply flex to action column, not empty state */
    .activities-table td:last-child:not(.empty-state) {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    /* Ensure empty state displays properly */
    .activities-table td.empty-state {
        display: table-cell !important;
    }

    /* Download Button - White style that stands out on blue header */
    .btn-download {
        background: white;
        color: #4e73df;
        padding: 12px 24px;
        border: 2px solid white;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-download:hover {
        background: #4e73df;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }

    /* Filter Section Layout */
    .filter-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-controls label {
        font-weight: 600;
        margin: 0;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .filter-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-controls {
            width: 100%;
        }
        
        .btn-download {
            width: 100%;
            justify-content: center;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .report-header h1 {
            font-size: 22px;
        }

        .report-header p {
            font-size: 14px;
        }

        .activities-table {
            font-size: 13px;
        }

        .activities-table thead th,
        .activities-table tbody td {
            padding: 8px 6px;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="report-page">
    <!-- Header Section -->
    <div class="report-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
    <!-- Left: Header Text -->
        <div style="flex: 1; min-width: 0;"> <!-- flex:1 lets it take available space but not push the button -->
            <h1>SOCIAL DEVELOPMENT PROGRAM</h1>
            <p>
                The social development program of the cooperative focuses on two (2) areas: a) for the cooperative itself and b) for the community.
                The source of fund for the first area may vary from Cooperative Education and Training Fund (CETF), optional fund or outright expense,
                while the second area is exclusive from the Community Development Fund (CDF).
            </p>
            <p>Report on Social Activities conducted with pictures/proof</p>
        </div>
    </div>

    <!-- Month Filter -->
    <div class="section-card" style="margin-bottom: 20px;">
        <div class="filter-container">
            <!-- Left: Filter Controls -->
            <div class="filter-controls">
                <label>Filter by Month:</label>
                <select id="monthFilter" class="form-control" style="max-width: 200px;" onchange="filterByMonth()">
                    <option value="">All Months</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select id="yearFilter" class="form-control" style="max-width: 150px;" onchange="filterByMonth()">
                    <option value="">All Years</option>
                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
                <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilter()">
                    <i class="fas fa-undo"></i> Clear Filter
                </button>
            </div>

            <!-- Right: Download Button -->
            <button type="button" class="btn btn-download" onclick="downloadPDF()">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Section A: For Cooperative Itself -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-users"></i>
            A. For Cooperative Itself
            <span class="section-subtitle">(Source of Fund: CETF, Optional Fund or Outright Expense)</span>
        </h2>

        <div class="table-responsive">
            <table class="activities-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Name of Activity</th>
                        <th style="width: 10%;">Date Conducted</th>
                        <th style="width: 12%;">Number of Participants</th>
                        <th style="width: 8%;">Total Amount Utilized</th>
                        <th style="width: 12%;">Source of Fund</th>
                        <th style="width: 20%;">Photos (Max 4)</th>
                        <th style="width: 8%;">Action</th>
                    </tr>
                </thead>
                <tbody id="cooperativeActivities">
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No activities recorded yet. Click "Add Activity" to add a new entry.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="addCooperativeActivity()">
                <i class="fas fa-plus"></i> Add Activity
            </button>
            <button type="button" class="btn btn-success" id="saveCoopBtn" onclick="saveCooperativeActivities()">
                <i class="fas fa-save"></i> Save All Activities
            </button>
        </div>
    </div>

    <!-- Section B: For The Community -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-hands-helping"></i>
            B. For The Community
            <span class="section-subtitle">(Source of Fund: CDF or Outright Expense)</span>
        </h2>

        <div class="table-responsive">
            <table class="activities-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Name of Activity</th>
                        <th style="width: 10%;">Date Conducted</th>
                        <th style="width: 12%;">Number of Participants</th>
                        <th style="width: 8%;">Total Amount Utilized</th>
                        <th style="width: 12%;">Source of Fund</th>
                        <th style="width: 20%;">Photos (Max 4)</th>
                        <th style="width: 8%;">Action</th>
                    </tr>
                </thead>
                <tbody id="communityActivities">
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No activities recorded yet. Click "Add Activity" to add a new entry.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="addCommunityActivity()">
                <i class="fas fa-plus"></i> Add Activity
            </button>
            <button type="button" class="btn btn-success" id="saveCommBtn" onclick="saveCommunityActivities()">
                <i class="fas fa-save"></i> Save All Activities
            </button>
        </div>
    </div>

</div>

<script>
let cooperativeRowCount = 0;
let communityRowCount = 0;
const MAX_PHOTOS = 4;

// Load activities on page load
document.addEventListener('DOMContentLoaded', function() {
    loadActivities();
});

// Load existing activities from database
function loadActivities() {
    fetch('{{ route("social-development.activities") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Load cooperative activities
                const coopActivities = data.activities.filter(a => a.activity_type === 'cooperative');
                coopActivities.forEach(activity => {
                    loadExistingActivity('cooperative', activity);
                });

                // Load community activities
                const commActivities = data.activities.filter(a => a.activity_type === 'community');
                commActivities.forEach(activity => {
                    loadExistingActivity('community', activity);
                });
            }
        })
        .catch(error => {
            console.error('Error loading activities:', error);
        });
}

// Load existing activity into table - COMPLETE FIXED VERSION
function loadExistingActivity(type, activity) {
    const tbody = document.getElementById(type === 'cooperative' ? 'cooperativeActivities' : 'communityActivities');
    
    // Remove empty state if exists
    const emptyState = tbody.querySelector('.empty-state');
    if (emptyState) tbody.innerHTML = '';

    const prefix = type === 'cooperative' ? 'coop' : 'comm';
    
    // Use activity ID for row ID to avoid duplicates
    const rowId = `${prefix}-row-${activity.id}`;
    
    // Remove existing row with same ID if it exists
    const existingRow = document.getElementById(rowId);
    if (existingRow) {
        existingRow.remove();
    }

    const row = document.createElement('tr');
    row.id = rowId;
    row.dataset.activityId = activity.id;

    // Extract and format date properly
    let formattedDate = '';
    let rawDate = '';
    if (activity.date_conducted) {
        // Extract YYYY-MM-DD from either "YYYY-MM-DD" or "YYYY-MM-DDTHH:MM:SS.000000Z"
        rawDate = activity.date_conducted.split('T')[0];
        
        // Format the date for display: "2026-01-07" -> "January 7, 2026"
        const dateObj = new Date(rawDate + 'T12:00:00'); // Add noon time to avoid timezone issues
        formattedDate = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    // Format amount as PHP currency
    const formattedAmount = activity.amount_utilized
        ? '₱' + Number(activity.amount_utilized).toLocaleString('en-PH', { minimumFractionDigits: 0 })
        : '₱0';

    row.innerHTML = `
        <td class="activity_name">${activity.activity_name}</td>
        <td class="date_conducted" data-date="${rawDate}">
            <span>${formattedDate}</span>
        </td>
        <td class="participants_count">${activity.participants_count ?? 0}</td>
        <td class="amount_utilized">${formattedAmount}</td>
        <td class="fund_source">${activity.fund_source}</td>
        <td>
            <div class="photo-preview">
                ${activity.photo_urls && activity.photo_urls.length > 0
                    ? activity.photo_urls.slice(0, 4).map(url => `<img src="${url}" alt="Activity photo">`).join('')
                    : 'No photos'}
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-success btn-edit" onclick="editActivity('${rowId}', ${activity.id}, '${type}')">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-danger" onclick="deleteActivity(${activity.id}, '${rowId}', '${type}Activities')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

// Edit Activity - FIXED VERSION WITH PHOTO EDITING
function editActivity(rowId, activityId, type) {
    const row = document.getElementById(rowId);
    if (!row) return;

    // Get current values
    const name = row.querySelector('.activity_name').textContent;
    const participants = row.querySelector('.participants_count').textContent;
    const amount = row.querySelector('.amount_utilized').textContent.replace(/₱|,/g, '');
    const fund = row.querySelector('.fund_source').textContent;
    
    // Get the date from data-date attribute (clean YYYY-MM-DD)
    const dateCell = row.querySelector('.date_conducted');
    const dateValue = dateCell ? dateCell.dataset.date : '';

    // Get existing photos HTML (already styled correctly)
    const photoCell = row.querySelector('td:nth-child(6)');
    const existingPhotosDiv = photoCell.querySelector('.photo-preview');
    const photoCount = existingPhotosDiv ? existingPhotosDiv.querySelectorAll('img').length : 0;
    const existingPhotosHTML = existingPhotosDiv ? existingPhotosDiv.innerHTML : 'No photos';

    // Replace cells with inputs
    row.querySelector('.activity_name').innerHTML = `<input type="text" class="form-input" value="${name}">`;
    row.querySelector('.participants_count').innerHTML = `<input type="number" class="form-input" value="${participants}" min="0">`;
    row.querySelector('.amount_utilized').innerHTML = `<input type="number" class="form-input" value="${amount}" min="0" step="0.01">`;
    row.querySelector('.fund_source').innerHTML = `
        <select class="form-select">
            <option value="CETF" ${fund === 'CETF' ? 'selected' : ''}>CETF</option>
            <option value="Optional Fund" ${fund === 'Optional Fund' ? 'selected' : ''}>Optional Fund</option>
            <option value="Outright Expense" ${fund === 'Outright Expense' ? 'selected' : ''}>Outright Expense</option>
            <option value="CDF" ${fund === 'CDF' ? 'selected' : ''}>CDF</option>
        </select>
    `;
    row.querySelector('.date_conducted').innerHTML = `<input type="date" class="form-input" value="${dateValue}">`;

    // Replace photos cell with file upload + existing photos preview (with proper styling)
    photoCell.innerHTML = `
        <div class="photo-upload">
            <div style="margin-bottom: 10px;">
                <strong style="font-size: 13px; color: #2c3e50; display: block; margin-bottom: 8px;">Current Photos (${photoCount}):</strong>
                <div class="photo-preview">
                    ${existingPhotosHTML}
                </div>
            </div>
            <div style="border-top: 1px solid #e3e6f0; padding-top: 10px; margin-top: 10px;">
                <label style="font-size: 13px; font-weight: 600; color: #4e73df; margin-bottom: 5px; display: block;">
                    Replace Photos:
                </label>
                <input type="file" class="form-input photo-input-edit" accept="image/*" multiple onchange="handlePhotoUploadEdit(this, 'edit-preview-${activityId}')">
                <div class="photo-limit-text">Upload new photos to replace existing ones (Max ${MAX_PHOTOS})</div>
                <div id="edit-preview-${activityId}" class="photo-preview"></div>
            </div>
        </div>
    `;

    // Replace Edit/Delete buttons with Save/Cancel
    const actionCell = row.querySelector('td:last-child');
    actionCell.innerHTML = `
        <button type="button" class="btn btn-primary" onclick="saveEditedActivity('${rowId}', ${activityId}, '${type}')">
            <i class="fas fa-save"></i>
        </button>
        <button type="button" class="btn btn-secondary" onclick="cancelEdit('${rowId}', ${activityId}, '${type}')">
            <i class="fas fa-times"></i>
        </button>
    `;
}

// Save Edited Activity - WITH PHOTO UPLOAD SUPPORT
function saveEditedActivity(rowId, activityId, type) {
    const row = document.getElementById(rowId);
    if (!row) return;

    const formData = new FormData();
    
    // Add Laravel method spoofing for PUT request
    formData.append('_method', 'PUT');
    
    // Add regular form data
    formData.append('activity_name', row.querySelector('.activity_name input').value);
    formData.append('date_conducted', row.querySelector('.date_conducted input').value);
    formData.append('participants_count', row.querySelector('.participants_count input').value);
    formData.append('amount_utilized', row.querySelector('.amount_utilized input').value);
    formData.append('fund_source', row.querySelector('.fund_source select').value);

    // Check if new photos were uploaded
    const photoInput = row.querySelector('.photo-input-edit');
    if (photoInput && photoInput.files.length > 0) {
        Array.from(photoInput.files).slice(0, MAX_PHOTOS).forEach((file, index) => {
            formData.append(`photos[${index}]`, file);
        });
    }

    console.log('=== SAVE ACTIVITY DEBUG ===');
    console.log('Activity ID:', activityId);
    console.log('Has new photos:', photoInput && photoInput.files.length > 0);
    
    // Log FormData contents for debugging
    console.log('FormData contents:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ':', pair[1] instanceof File ? pair[1].name : pair[1]);
    }

    fetch(`/social-development/activities/${activityId}`, {
        method: 'POST', // POST with _method=PUT for file uploads
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            // Don't set Content-Type - let browser set it with boundary for multipart/form-data
        },
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(res => {
        console.log('Response from server:', res);
        
        if (res.success) {
            alert(res.message);
            
            // Reload the activity with fresh data
            loadExistingActivity(type, res.activity);
        } else {
            alert('Error: ' + (res.message || 'Failed to update'));
            if (res.errors) {
                console.error('Validation errors:', res.errors);
            }
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('An error occurred while updating activity: ' + err.message);
    });
}

// Cancel Edit - FIXED VERSION
function cancelEdit(rowId, activityId, type) {
    // Reload original data from server
    fetch('{{ route("social-development.activities") }}')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const activity = data.activities.find(a => a.id === activityId);
                if (activity) {
                    loadExistingActivity(type, activity);
                }
            }
        })
        .catch(err => {
            console.error('Error reloading activity:', err);
            location.reload();
        });
}

// Add Cooperative Activity Row
function addCooperativeActivity() {
    const tbody = document.getElementById('cooperativeActivities');
    const emptyState = tbody.querySelector('.empty-state');
    if (emptyState) {
        tbody.innerHTML = '';
    }

    cooperativeRowCount++;
    const row = document.createElement('tr');
    row.id = `coop-row-${cooperativeRowCount}`;
    row.innerHTML = `
        <td><input type="text" class="form-input" name="coop_activity_name[]" placeholder="Enter activity name" required></td>
        <td><input type="date" class="form-input" name="coop_date_conducted[]" required></td>
        <td><input type="number" class="form-input" name="coop_participants[]" placeholder="0" min="0" required></td>
        <td><input type="number" class="form-input" name="coop_amount[]" placeholder="0" step="0.01" min="0" required></td>
        <td>
            <select class="form-select" name="coop_fund_source[]" required>
                <option value="">Select Source</option>
                <option value="CETF">CETF</option>
                <option value="Optional Fund">Optional Fund</option>
                <option value="Outright Expense">Outright Expense</option>
            </select>
        </td>
        <td>
            <div class="photo-upload">
                <input type="file" class="form-input photo-input" accept="image/*" multiple onchange="handlePhotoUpload(this, 'coop-preview-${cooperativeRowCount}')">
                <div class="photo-limit-text">Maximum ${MAX_PHOTOS} photos allowed</div>
                <div id="coop-preview-${cooperativeRowCount}" class="photo-preview"></div>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-danger" onclick="removeRow('coop-row-${cooperativeRowCount}', 'cooperativeActivities')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}

// Add Community Activity Row
function addCommunityActivity() {
    const tbody = document.getElementById('communityActivities');
    const emptyState = tbody.querySelector('.empty-state');
    if (emptyState) {
        tbody.innerHTML = '';
    }

    communityRowCount++;
    const row = document.createElement('tr');
    row.id = `comm-row-${communityRowCount}`;
    row.innerHTML = `
        <td><input type="text" class="form-input" name="comm_activity_name[]" placeholder="Enter activity name" required></td>
        <td><input type="date" class="form-input" name="comm_date_conducted[]" required></td>
        <td><input type="number" class="form-input" name="comm_participants[]" placeholder="0" min="0" required></td>
        <td><input type="number" class="form-input" name="comm_amount[]" placeholder="0" step="0.01" min="0" required></td>
        <td>
            <select class="form-select" name="comm_fund_source[]" required>
                <option value="">Select Source</option>
                <option value="CDF">CDF</option>
                <option value="Outright Expense">Outright Expense</option>
            </select>
        </td>
        <td>
            <div class="photo-upload">
                <input type="file" class="form-input photo-input" accept="image/*" multiple onchange="handlePhotoUpload(this, 'comm-preview-${communityRowCount}')">
                <div class="photo-limit-text">Maximum ${MAX_PHOTOS} photos allowed</div>
                <div id="comm-preview-${communityRowCount}" class="photo-preview"></div>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-danger" onclick="removeRow('comm-row-${communityRowCount}', 'communityActivities')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}

// Replace the downloadPDF function in your Blade file with this:

function downloadPDF() {
    // Get current filter values
    const month = document.getElementById('monthFilter')?.value || '';
    const year = document.getElementById('yearFilter')?.value || '';

    // Build URL with query parameters
    let url = '{{ route("social-report.pdf") }}';
    const params = new URLSearchParams();
    
    if (month) params.append('month', month);
    if (year) params.append('year', year);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }

    // Open PDF in new tab or download
    window.open(url, '_blank');
}

// Remove Row
function removeRow(rowId, tbodyId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
        const tbody = document.getElementById(tbodyId);
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>No activities recorded yet. Click "Add Activity" to add a new entry.</p>
                    </td>
                </tr>

            `;
        }
    }
}

// Handle Photo Upload with Max Limit
function handlePhotoUpload(input, previewId) {
    const files = Array.from(input.files);
    
    // Check if exceeded max photos
    if (files.length > MAX_PHOTOS) {
        alert(`You can only upload a maximum of ${MAX_PHOTOS} photos per activity.`);
        input.value = ''; // Clear the file input
        return;
    }
    
    previewPhotos(input, previewId);
}

// Preview Photos
function previewPhotos(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';

    if (input.files) {
        const filesToShow = Array.from(input.files).slice(0, MAX_PHOTOS);
        
        filesToShow.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.title = file.name;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
}

// Handle Photo Upload in Edit Mode
function handlePhotoUploadEdit(input, previewId) {
    const files = Array.from(input.files);
    
    // Check if exceeded max photos
    if (files.length > MAX_PHOTOS) {
        alert(`You can only upload a maximum of ${MAX_PHOTOS} photos per activity.`);
        input.value = ''; // Clear the file input
        return;
    }
    
    previewPhotos(input, previewId);
}

// Save Cooperative Activities - Fixed Version
function saveCooperativeActivities() {
    const tbody = document.getElementById('cooperativeActivities');
    const rows = tbody.querySelectorAll('tr:not(:has(.empty-state))');

    const newRows = Array.from(rows).filter(row => !row.dataset.activityId);

    if (newRows.length === 0) {
        alert('No new activities to save.');
        return;
    }

    const formData = new FormData();
    const activities = [];

    console.log('Found ' + newRows.length + ' new rows to save');

    newRows.forEach((row, index) => {
        const activity = {
            activity_name: row.querySelector('input[name="coop_activity_name[]"]').value,
            date_conducted: row.querySelector('input[name="coop_date_conducted[]"]').value,
            participants_count: row.querySelector('input[name="coop_participants[]"]').value,
            amount_utilized: row.querySelector('input[name="coop_amount[]"]').value,
            fund_source: row.querySelector('select[name="coop_fund_source[]"]').value,
        };

        console.log('Activity ' + index + ':', activity);

        if (!activity.activity_name || !activity.date_conducted || !activity.fund_source) {
            alert('Please fill in all required fields.');
            throw new Error('Validation failed');
        }

        activities.push(activity);

        // Add photos (max 4)
        const photoInput = row.querySelector('.photo-input');
        if (photoInput && photoInput.files.length > 0) {
            console.log('Found ' + photoInput.files.length + ' photos for activity ' + index);
            Array.from(photoInput.files).slice(0, 4).forEach((file, photoIndex) => {
                formData.append(`activities[${index}][photos][${photoIndex}]`, file);
                console.log('Appending photo:', file.name);
            });
        }
    });

    // Send activities as JSON string
    formData.append('activities', JSON.stringify(activities));
    
    console.log('Activities JSON:', JSON.stringify(activities));
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
    }

    const saveBtn = document.getElementById('saveCoopBtn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    fetch('{{ route("social-development.cooperative.save") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(res => {
        console.log('Response status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to save activities'));
            console.error('Server error:', data);
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('An error occurred while saving activities. Check console for details.');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Save All Activities';
    });
}

// Save Community Activities - Fixed Version
function saveCommunityActivities() {
    const tbody = document.getElementById('communityActivities');
    const rows = tbody.querySelectorAll('tr:not(:has(.empty-state))');

    const newRows = Array.from(rows).filter(row => !row.dataset.activityId);

    if (newRows.length === 0) {
        alert('No new activities to save.');
        return;
    }

    const formData = new FormData();
    const activities = [];

    console.log('Found ' + newRows.length + ' new community rows to save');

    newRows.forEach((row, index) => {
        const activity = {
            activity_name: row.querySelector('input[name="comm_activity_name[]"]').value,
            date_conducted: row.querySelector('input[name="comm_date_conducted[]"]').value,
            participants_count: row.querySelector('input[name="comm_participants[]"]').value,
            amount_utilized: row.querySelector('input[name="comm_amount[]"]').value,
            fund_source: row.querySelector('select[name="comm_fund_source[]"]').value,
        };

        console.log('Community Activity ' + index + ':', activity);

        if (!activity.activity_name || !activity.date_conducted || !activity.fund_source) {
            alert('Please fill in all required fields.');
            throw new Error('Validation failed');
        }

        activities.push(activity);

        // Add photos (max 4)
        const photoInput = row.querySelector('.photo-input');
        if (photoInput && photoInput.files.length > 0) {
            console.log('Found ' + photoInput.files.length + ' photos for community activity ' + index);
            Array.from(photoInput.files).slice(0, MAX_PHOTOS).forEach((file, photoIndex) => {
                formData.append(`activities[${index}][photos][${photoIndex}]`, file);
                console.log('Appending photo:', file.name);
            });
        }
    });

    // Send activities as JSON string
    formData.append('activities', JSON.stringify(activities));
    
    console.log('Community Activities JSON:', JSON.stringify(activities));
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
    }

    const saveBtn = document.getElementById('saveCommBtn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    fetch('{{ route("social-development.community.save") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(res => {
        console.log('Community Response status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('Community Response data:', data);
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to save activities'));
            console.error('Server error:', data);
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('An error occurred while saving activities. Check console for details.');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Save All Activities';
    });
}


// Delete Activity
function deleteActivity(activityId, rowId, tbodyId) {
    if (!confirm('Are you sure you want to delete this activity?')) {
        return;
    }

    fetch(`/social-development/activities/${activityId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            removeRow(rowId, tbodyId);
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the activity.');
    });
}

// Filter by Month and Year
function filterByMonth() {
    const month = document.getElementById('monthFilter').value;
    const year = document.getElementById('yearFilter').value;

    filterTableRows('cooperativeActivities', month, year);
    filterTableRows('communityActivities', month, year);
}

function filterTableRows(tbodyId, month, year) {
    const tbody = document.getElementById(tbodyId);
    const rows = tbody.querySelectorAll('tr');
    let visibleCount = 0;

    rows.forEach(row => {
        // Skip empty-state row
        if (row.querySelector('.empty-state')) return;

        const dateCell = row.querySelector('.date_conducted');
        if (!dateCell) {
            row.style.display = '';
            visibleCount++;
            return;
        }

        // Use the raw YYYY-MM-DD date from data-date attribute
        const rawDate = dateCell.dataset.date;
        if (!rawDate) {
            row.style.display = '';
            visibleCount++;
            return;
        }

        const rowMonth = rawDate.substring(5, 7);
        const rowYear = rawDate.substring(0, 4);

        let showRow = true;
        if (month && rowMonth !== month) showRow = false;
        if (year && rowYear !== year) showRow = false;

        row.style.display = showRow ? '' : 'none';
        if (showRow) visibleCount++;
    });

    // Show empty state only when nothing is visible
    const emptyStateRow = tbody.querySelector('tr .empty-state')?.closest('tr');
    if (emptyStateRow) {
        emptyStateRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}

function clearFilter() {
    document.getElementById('monthFilter').value = '';
    document.getElementById('yearFilter').value = '';
    filterByMonth();
}

</script>
@endsection