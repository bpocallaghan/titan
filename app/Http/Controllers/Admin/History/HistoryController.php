<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\History;

use App\Http\Requests;
use Bpocallaghan\Titan\Models\LogActivity;
use Bpocallaghan\Titan\Models\LogAdminActivity;
use Bpocallaghan\Titan\Http\Controllers\Admin\TitanAdminController;

class HistoryController extends TitanAdminController
{
    public function website()
    {
        $actions = LogActivity::getLatest();

        return $this->view('titan::history.website', compact('actions'));
    }

    public function admin()
    {
        $activities = LogAdminActivity::getLatest();

        return $this->view('titan::history.admin', compact('activities'));
    }
}