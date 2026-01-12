<style>
/* Attendance Modal Styles (for nested modals) */
.attendance-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.attendance-modal-container {
    background: white;
    border-radius: 15px;
    width: 95%;
    max-width: 1400px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

.attendance-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.attendance-modal-header h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    line-height: 1;
}

.modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.attendance-modal-body {
    padding: 30px;
    overflow-y: auto;
    flex: 1;
}

/* Full Details Styles */
.full-details-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.full-details-section h4 {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.full-details-section h4 i {
    color: #667eea;
}

.full-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 15px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.detail-value {
    font-size: 14px;
    color: #2c3e50;
    font-weight: 500;
    display: inline-flex;
    width: auto;
}

/* Attendance Table Styles */
.attendance-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.attendance-table thead {
    background: #f8f9fc;
}

.attendance-table thead th {
    padding: 15px 20px;
    text-align: left;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #2c3e50;
    border-bottom: 2px solid #e3e6f0;
}

.attendance-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.attendance-table tbody tr:hover {
    background: #f8f9fc;
}

.attendance-table tbody tr:last-child {
    border-bottom: none;
}

.attendance-table tbody td {
    padding: 15px 20px;
    color: #495057;
    font-size: 14px;
}

.attendance-table tbody td strong {
    color: #2c3e50;
    font-weight: 600;
}

/* Status Badge Styles */
.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    width: fit-content;
    flex: 0 0 auto;
    white-space: nowrap;
    text-transform: capitalize;
}

.status-badge.status-active {
    background: #d4edda;
    color: #155724;
}

.status-badge.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-badge.status-pending {
    background: #fff3cd;
    color: #856404;
}

/* Button Styles */
.btn-view-details-small {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}

.btn-view-details-small:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

/* Nested Modal */
.nested-modal {
    background: rgba(0, 0, 0, 0.7);
}

/* Attendance Modal Styles (for nested modals) */
.attendance-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

</style>
