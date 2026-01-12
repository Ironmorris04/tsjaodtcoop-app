@php
use Illuminate\Support\Facades\Storage;
@endphp

<style>
    .profile-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 30px;
        border-radius: 10px 10px 0 0;
        margin: -16px -16px 20px -16px;
    }

    .profile-photo-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-photo-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-photo-placeholder i {
        font-size: 60px;
        color: white;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 15px 0 5px 0;
    }

    .profile-role {
        color: #64748b;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-section {
        margin-bottom: 30px;
    }

    .info-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-section-title i {
        color: #4e73df;
        font-size: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 500;
    }

    .badge-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
    }

    .info-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .info-badge.yes {
        background: #d4edda;
        color: #155724;
    }

    .info-badge.no {
        background: #f8d7da;
        color: #721c24;
    }

    .ids-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .ids-table th {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #cbd5e1;
    }

    .ids-table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        color: #1e293b;
    }

    .ids-table tr:last-child td {
        border-bottom: none;
    }

    .ids-table tr:hover {
        background: #f8fafc;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.valid {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.expired {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.expiring {
        background: #fff3cd;
        color: #856404;
    }

    .no-ids-message {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .no-ids-message i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #cbd5e1;
    }

    /* ID Photo Styles */
    .id-photo {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .id-photo:hover {
        border-color: #4e73df;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
        transform: scale(1.05);
    }

    .id-photo-placeholder {
        width: 80px;
        height: 60px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 6px;
        border: 2px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    .id-photo-placeholder i {
        font-size: 24px;
    }

    /* Edit Mode Styles */
    .edit-mode-controls {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .profile-edit-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .profile-edit-btn.primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
    }

    .profile-edit-btn.primary:hover {
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        transform: translateY(-2px);
    }

    .profile-edit-btn.success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
    }

    .profile-edit-btn.success:hover {
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.4);
        transform: translateY(-2px);
    }

    .profile-edit-btn.secondary {
        background: #6c757d;
        color: white;
    }

    .profile-edit-btn.secondary:hover {
        background: #5a6268;
    }

    .profile-edit-btn.warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        color: white;
    }

    .profile-edit-btn.warning:hover {
        box-shadow: 0 4px 12px rgba(246, 194, 62, 0.4);
        transform: translateY(-2px);
    }

    /* Form Input Styles */
    .profile-input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        font-size: 15px;
        color: #1e293b;
        transition: all 0.3s ease;
    }

    .profile-input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .profile-input:disabled {
        background: #f8fafc;
        cursor: not-allowed;
    }

    /* Photo Upload Styles */
    .photo-upload-container {
        position: relative;
        display: inline-block;
    }

    .photo-upload-overlay {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .photo-upload-overlay i {
        color: white;
        font-size: 16px;
    }

    .edit-mode .photo-upload-overlay {
        display: flex;
    }

    .photo-upload-overlay:hover {
        transform: scale(1.1);
    }

    .file-input-hidden {
        display: none;
    }
</style>

<!-- Full-Screen ID Photo Viewer -->
<div id="fullscreenPhotoViewer" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.95); z-index: 9999; cursor: zoom-out;">
    <div style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); color: white; font-size: 24px; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.8); z-index: 10000;">
        <i class="fas fa-id-card"></i> <span id="fullscreenPhotoTitle"></span>
    </div>
    <button onclick="closeFullscreenPhoto()" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); border: 2px solid white; color: white; font-size: 24px; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; z-index: 10000; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='rotate(90deg)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='rotate(0deg)'">
        <i class="fas fa-times"></i>
    </button>
    <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: white; font-size: 14px; opacity: 0.8; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">
        <i class="fas fa-info-circle"></i> Click anywhere or press ESC to close
    </div>
    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 80px 40px 60px 40px;">
        <img id="fullscreenPhotoImage" src="" alt="ID Photo" style="max-width: 100%; max-height: 100%; object-fit: contain; box-shadow: 0 10px 50px rgba(0,0,0,0.8); border-radius: 8px;" onclick="event.stopPropagation();">
    </div>
</div>

<style>
    #fullscreenPhotoViewer {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    #fullscreenPhotoImage {
        animation: zoomIn 0.3s ease;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

<script>
function showIdPhotoModal(imageUrl, idType) {
    const viewer = document.getElementById('fullscreenPhotoViewer');
    const image = document.getElementById('fullscreenPhotoImage');
    const title = document.getElementById('fullscreenPhotoTitle');

    image.src = imageUrl;
    title.textContent = idType;
    viewer.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeFullscreenPhoto() {
    const viewer = document.getElementById('fullscreenPhotoViewer');
    viewer.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close on click anywhere in the viewer
document.addEventListener('DOMContentLoaded', function() {
    const viewer = document.getElementById('fullscreenPhotoViewer');
    if (viewer) {
        viewer.addEventListener('click', closeFullscreenPhoto);
    }
});

// Close on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFullscreenPhoto();
    }
});
</script>

@php
    $detail = $operator->operatorDetail;
    $ids = $operator->operatorIds;
@endphp

<div class="profile-header" id="profileContainer">
    <div class="profile-photo-container">
        <div class="photo-upload-container">
            @if($detail && $detail->profile_photo_url)
                <img src="{{ $detail->profile_photo_url }}" alt="Profile Photo" class="profile-photo" id="profilePhotoPreview">
            @else
                <div class="profile-photo-placeholder" id="profilePhotoPreview">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <div class="photo-upload-overlay" onclick="document.getElementById('profilePhotoInput').click()">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        <input type="file" id="profilePhotoInput" class="file-input-hidden" accept="image/*" onchange="previewProfilePhoto(this)">
    </div>
    <div style="text-align: center;">
        <h3 class="profile-name">{{ $detail ? $detail->full_name : $operator->contact_person }}</h3>
        <p class="profile-role">Operator</p>
    </div>

    <!-- Action Buttons -->
    <div class="edit-mode-controls">
        <button type="button" class="profile-edit-btn primary" id="editProfileBtn" onclick="enableEditMode()">
            <i class="fas fa-edit"></i> Edit Profile
        </button>
        <button type="button" class="profile-edit-btn warning" onclick="showChangePasswordModal()">
            <i class="fas fa-key"></i> Change Password
        </button>
        <button type="button" class="profile-edit-btn primary" onclick="viewApplicationForm()">
            <i class="fas fa-file-alt"></i> View Application Form
        </button>
        <button type="button" class="profile-edit-btn success" id="saveProfileBtn" style="display: none;" onclick="saveProfile()">
            <i class="fas fa-save"></i> Save Changes
        </button>
        <button type="button" class="profile-edit-btn secondary" id="cancelEditBtn" style="display: none;" onclick="cancelEdit()">
            <i class="fas fa-times"></i> Cancel
        </button>
    </div>
</div>

<!-- Personal Information -->
<div class="info-section">
    <h4 class="info-section-title">
        <i class="fas fa-user-circle"></i>
        Personal Information
    </h4>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Full Name</span>
            <span class="info-value view-mode">{{ $detail ? $detail->full_name : 'N/A' }}</span>
            <input type="text" class="profile-input edit-mode" style="display: none;" id="input_full_name" value="{{ $detail ? $detail->full_name : '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Age</span>
            <span class="info-value view-mode">{{ $detail?->age ? $detail->age . ' years old' : 'N/A' }}</span>
            <input type="number" class="profile-input edit-mode" style="display: none;" id="input_age" value="{{ $detail?->age ?? '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Gender</span>
            <span class="info-value view-mode">{{ $detail ? ucfirst($detail->sex) : 'N/A' }}</span>
            <select class="profile-input edit-mode" style="display: none;" id="input_sex" disabled>
                <option value="male" {{ $detail?->sex === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $detail?->sex === 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="info-item">
            <span class="info-label">Contact Number</span>
            <span class="info-value view-mode">{{ $operator->phone ?? 'N/A' }}</span>
            <input type="tel" class="profile-input edit-mode" style="display: none;" id="input_phone" value="{{ $operator->phone ?? '' }}">
        </div>
        <div class="info-item">
            <span class="info-label">Email Address</span>
            <span class="info-value view-mode">{{ $operator->email ?? 'N/A' }}</span>
            <input type="email" class="profile-input edit-mode" style="display: none;" id="input_email" value="{{ $operator->email ?? '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Address</span>
            <span class="info-value view-mode">{{ $operator->address ?? 'N/A' }}</span>
            <input type="text" class="profile-input edit-mode" style="display: none;" id="input_address" value="{{ $operator->address ?? '' }}">
        </div>
        <div class="info-item">
            <span class="info-label">Birthdate</span>
            <span class="info-value view-mode">{{ $detail?->birthdate ? $detail->birthdate->format('F d, Y') : 'N/A' }}</span>
            <input type="date" class="profile-input edit-mode" style="display: none;" id="input_birthdate" value="{{ $detail?->birthdate?->format('Y-m-d') ?? '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Birthplace</span>
            <span class="info-value view-mode">{{ $detail->birthplace ?? 'N/A' }}</span>
            <input type="text" class="profile-input edit-mode" style="display: none;" id="input_birthplace" value="{{ $detail->birthplace ?? '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Civil Status</span>
            <span class="info-value view-mode">{{ $detail ? ucfirst($detail->civil_status) : 'N/A' }}</span>
            <select class="profile-input edit-mode" style="display: none;" id="input_civil_status" disabled>
                <option value="single" {{ $detail?->civil_status === 'single' ? 'selected' : '' }}>Single</option>
                <option value="married" {{ $detail?->civil_status === 'married' ? 'selected' : '' }}>Married</option>
                <option value="widowed" {{ $detail?->civil_status === 'widowed' ? 'selected' : '' }}>Widowed</option>
                <option value="separated" {{ $detail?->civil_status === 'separated' ? 'selected' : '' }}>Separated</option>
            </select>
        </div>
        <div class="info-item">
            <span class="info-label">Citizenship</span>
            <span class="info-value view-mode">{{ $detail->citizenship ?? 'N/A' }}</span>
            <input type="text" class="profile-input edit-mode" style="display: none;" id="input_citizenship" value="{{ $detail->citizenship ?? '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Religion</span>
            <span class="info-value view-mode">{{ $detail->religion ?? 'N/A' }}</span>
            <input type="text" class="profile-input edit-mode" style="display: none;" id="input_religion" value="{{ $detail->religion ?? '' }}" readonly>
        </div>
        <div class="info-item">
            <span class="info-label">Occupation</span>
            <span class="info-value view-mode">{{ $detail->occupation ?? 'N/A' }}</span>
            <input type="text" class="profile-input edit-mode" style="display: none;" id="input_occupation" value="{{ $detail->occupation ?? '' }}" readonly>
        </div>
    </div>
</div>

<!-- Additional Information -->
@if($detail)
<div class="info-section">
    <h4 class="info-section-title">
        <i class="fas fa-info-circle"></i>
        Additional Information
    </h4>
    <div class="badge-group view-mode">
        <span class="info-badge {{ $detail->indigenous_people === 'yes' ? 'yes' : 'no' }}">
            <i class="fas {{ $detail->indigenous_people === 'yes' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            Indigenous People: {{ $detail->indigenous_people === 'yes' ? 'Yes' : 'No' }}
        </span>
        <span class="info-badge {{ $detail->pwd === 'yes' ? 'yes' : 'no' }}">
            <i class="fas {{ $detail->pwd === 'yes' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            Person with Disability: {{ $detail->pwd === 'yes' ? 'Yes' : 'No' }}
        </span>
        <span class="info-badge {{ $detail->senior_citizen === 'yes' ? 'yes' : 'no' }}">
            <i class="fas {{ $detail->senior_citizen === 'yes' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            Senior Citizen: {{ $detail->senior_citizen === 'yes' ? 'Yes' : 'No' }}
        </span>
        <span class="info-badge {{ $detail->fourps_beneficiary === 'yes' ? 'yes' : 'no' }}">
            <i class="fas {{ $detail->fourps_beneficiary === 'yes' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            4Ps Member: {{ $detail->fourps_beneficiary === 'yes' ? 'Yes' : 'No' }}
        </span>
    </div>
    <div class="info-grid edit-mode" style="display: none;">
        <div class="info-item">
            <span class="info-label">Indigenous People</span>
            <select class="profile-input" id="input_indigenous_people" disabled>
                <option value="no" {{ $detail->indigenous_people !== 'yes' ? 'selected' : '' }}>No</option>
                <option value="yes" {{ $detail->indigenous_people === 'yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div class="info-item">
            <span class="info-label">Person with Disability</span>
            <select class="profile-input" id="input_pwd" disabled>
                <option value="no" {{ $detail->pwd !== 'yes' ? 'selected' : '' }}>No</option>
                <option value="yes" {{ $detail->pwd === 'yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div class="info-item">
            <span class="info-label">Senior Citizen</span>
            <select class="profile-input" id="input_senior_citizen" disabled>
                <option value="no" {{ $detail->senior_citizen !== 'yes' ? 'selected' : '' }}>No</option>
                <option value="yes" {{ $detail->senior_citizen === 'yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div class="info-item">
            <span class="info-label">4Ps Member</span>
            <select class="profile-input" id="input_fourps_beneficiary" disabled>
                <option value="no" {{ $detail->fourps_beneficiary !== 'yes' ? 'selected' : '' }}>No</option>
                <option value="yes" {{ $detail->fourps_beneficiary === 'yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
    </div>
</div>
@endif

<!-- Operator ID -->
<div class="info-section">
    <h4 class="info-section-title">
        <i class="fas fa-id-card"></i>
        Identification Document
    </h4>

    @if($detail && $detail->id_type && $detail->id_number)
        <div class="view-mode">
            <table class="ids-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>ID Type</th>
                        <th>ID Number</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @if($detail->valid_id_url)
                                <img src="{{ $detail->valid_id_url }}" alt="{{ $detail->formatted_id_type }}" class="id-photo" onclick="showIdPhotoModal('{{ $detail->valid_id_url }}', '{{ $detail->formatted_id_type }}')">
                            @else
                                <div class="id-photo-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $detail->formatted_id_type }}</strong></td>
                        <td>{{ $detail->id_number }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="edit-mode" style="display: none;">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ID Type</span>
                    <input type="text" class="profile-input" id="input_id_type" value="{{ $detail->formatted_id_type }}" readonly>
                </div>
                <div class="info-item">
                    <span class="info-label">ID Number</span>
                    <input type="text" class="profile-input" id="input_id_number" value="{{ $detail->id_number }}">
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">Valid ID Photo</span>
                    <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                        @if($detail->valid_id_url)
                            <img src="{{ $detail->valid_id_url }}" alt="Valid ID" id="validIdPreview" class="id-photo" style="width: 150px; height: 100px;">
                        @else
                            <div class="id-photo-placeholder" id="validIdPreview" style="width: 150px; height: 100px;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <button type="button" class="profile-edit-btn primary" onclick="document.getElementById('validIdInput').click()">
                            <i class="fas fa-upload"></i> Upload New ID Photo
                        </button>
                        <input type="file" id="validIdInput" class="file-input-hidden" accept="image/*" onchange="previewValidId(this)">
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="no-ids-message">
            <i class="fas fa-id-card"></i>
            <p>No identification document on record</p>
        </div>
    @endif
</div>

<!-- JavaScript Functions -->
<script>
let isEditMode = false;
let profilePhotoFile = null;
let validIdFile = null;

// Enable edit mode
function enableEditMode() {
    isEditMode = true;
    document.getElementById('profileContainer').classList.add('edit-mode');

    // Show/hide buttons
    document.getElementById('editProfileBtn').style.display = 'none';
    document.getElementById('saveProfileBtn').style.display = 'inline-flex';
    document.getElementById('cancelEditBtn').style.display = 'inline-flex';

    // Toggle view/edit mode elements
    document.querySelectorAll('.view-mode').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'block');
}

// Cancel edit mode
function cancelEdit() {
    isEditMode = false;
    document.getElementById('profileContainer').classList.remove('edit-mode');

    // Show/hide buttons
    document.getElementById('editProfileBtn').style.display = 'inline-flex';
    document.getElementById('saveProfileBtn').style.display = 'none';
    document.getElementById('cancelEditBtn').style.display = 'none';

    // Toggle view/edit mode elements
    document.querySelectorAll('.view-mode').forEach(el => el.style.display = '');
    document.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');

    // Reset file inputs
    profilePhotoFile = null;
    validIdFile = null;
    document.getElementById('profilePhotoInput').value = '';
    if (document.getElementById('validIdInput')) {
        document.getElementById('validIdInput').value = '';
    }

    // Reload profile to reset changes
    $('#operatorProfileModal').modal('hide');
    setTimeout(() => $('#operatorProfileModal').modal('show'), 300);
}

// Preview profile photo
function previewProfilePhoto(input) {
    if (input.files && input.files[0]) {
        profilePhotoFile = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('profilePhotoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Replace placeholder with image
                preview.outerHTML = `<img src="${e.target.result}" alt="Profile Photo" class="profile-photo" id="profilePhotoPreview">`;
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Preview valid ID
function previewValidId(input) {
    if (input.files && input.files[0]) {
        validIdFile = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('validIdPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Replace placeholder with image
                preview.outerHTML = `<img src="${e.target.result}" alt="Valid ID" id="validIdPreview" class="id-photo" style="width: 150px; height: 100px;">`;
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Save profile changes
function saveProfile() {
    // Show loading state
    const saveBtn = document.getElementById('saveProfileBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    // Prepare form data
    const formData = new FormData();

    // Add text fields
    const phoneValue = document.getElementById('input_phone').value;
    formData.append('phone', phoneValue);
    formData.append('address', document.getElementById('input_address').value);
    formData.append('id_number', document.getElementById('input_id_number')?.value || '');

    // Add profile photo if changed
    if (profilePhotoFile) {
        formData.append('profile_photo', profilePhotoFile);
    }

    // Add valid ID if changed
    if (validIdFile) {
        formData.append('valid_id', validIdFile);
    }

    // Send AJAX request
    fetch(appUrl('operator/profile/update'), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.appConfig.csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            showSuccess('Profile updated successfully!');

            // Close modal and reload
            $('#operatorProfileModal').modal('hide');
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            // Show validation errors
            if (data.errors) {
                let errorMessage = 'Please fix the following errors:\n\n';
                for (const [field, messages] of Object.entries(data.errors)) {
                    errorMessage += `• ${messages.join(', ')}\n`;
                }
                showError(errorMessage);
            } else {
                showError(data.message || 'Failed to update profile');
            }
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while updating profile');
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Show change password modal
function showChangePasswordModal() {
    $('#operatorProfileModal').modal('hide');
    $('#changePasswordModal').modal('show');
}

// View application form (handles both PDF and images from S3)
function viewApplicationForm() {
    const formPath = '{{ $operator->membership_form_path ?? "" }}';
    
    if (!formPath) {
        alert('No application form available');
        return;
    }
    
    // Generate S3 URL using Laravel helper
    const formUrl = '{{ $operator->membership_form_path ? Storage::disk("s3")->url($operator->membership_form_path) : "" }}';
    
    if (!formUrl) {
        alert('Unable to load application form');
        return;
    }
    
    // Detect file extension
    const ext = formPath.split('.').pop().toLowerCase();

    if (ext === 'pdf') {
        // Fetch PDF from S3 and open it inline
        fetch(formUrl)
            .then(res => {
                if (!res.ok) throw new Error('Failed to load PDF');
                return res.blob();
            })
            .then(blob => {
                const blobUrl = URL.createObjectURL(blob);
                window.open(blobUrl, '_blank');
            })
            .catch(err => {
                console.error(err);
                alert('Failed to open PDF. Please try again.');
            });
    } else {
        // For images, just open the S3 URL directly
        window.open(formUrl, '_blank');
    }
}

</script>
