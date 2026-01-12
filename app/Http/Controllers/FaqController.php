<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display FAQs for Admin
     */
    public function adminFaqs()
    {
        return view('system_help.admin_faqs');
    }

    /**
     * Display FAQs for Operator
     */
    public function operatorFaqs()
    {
        return view('system_help.operator_faqs');
    }

    /**
     * Display FAQs for Treasurer
     */
    public function treasurerFaqs()
    {
        return view('system_help.treasurer_faqs');
    }

    /**
     * Display FAQs for President
     */
    public function presidentFaqs()
    {
        return view('system_help.president_faqs');
    }

    /**
     * Display FAQs for Auditor
     */
    public function auditorFaqs()
    {
        return view('system_help.auditor_faqs');
    }
}
