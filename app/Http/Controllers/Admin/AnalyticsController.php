<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin;

use App\Http\Requests;

class AnalyticsController extends AdminController
{
    public function summary()
    {
        return $this->view('titan::analytics.summary');
    }

    public function devices()
    {
        return $this->view('titan::analytics.devices');
    }

    public function visitsReferrals()
    {
        return $this->view('titan::analytics.visits_referrals');
    }

    public function interests()
    {
        return $this->view('titan::analytics.interests');
    }

    public function demographics()
    {
        return $this->view('titan::analytics.demographics');
    }
}