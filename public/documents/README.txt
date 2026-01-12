MEMBERSHIP APPLICATION FORM - DYNAMIC PDF GENERATION
=====================================================

The membership application form is now dynamically generated using DomPDF.
The system automatically pre-fills the form with operator registration data.

HOW IT WORKS:
-------------
1. When an operator completes registration, their data is stored in the database
2. The registration-pending page provides a download link with the operator_id
3. The MembershipFormController fetches the operator's data from:
   - operator_details table (personal information)
   - operators table (contact information)
   - operator_dependents table (dependent information)
4. A PDF is generated using the membership-form-pdf.blade.php template
5. The PDF is pre-filled with all available registration data

PRE-FILLED FIELDS:
------------------
- Applicant's full name (Last, First, Middle)
- Date of birth and place of birth
- Sex and civil status
- Citizenship and religion
- Occupation
- Complete address
- Contact number and email
- ID type and ID number
- Special classifications (Indigenous People, PWD, Senior Citizen, 4Ps)
- Dependents information (name, age, relationship)

LEGACY SUPPORT:
---------------
A static PDF file can still be placed in this directory for backward compatibility:
    membership_application_form.pdf

If the static file exists, it will be served for blank form requests.
Otherwise, a dynamically generated blank form will be provided.

TECHNICAL DETAILS:
------------------
Controller: app\Http\Controllers\MembershipFormController.php
View Template: resources\views\membership-form-pdf.blade.php
Route: /download/membership-form?operator_id={id}
PDF Library: DomPDF (barryvdh/laravel-dompdf)
Paper Size: A4 Portrait
