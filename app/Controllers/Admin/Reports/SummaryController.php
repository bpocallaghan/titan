<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Reports;

use App\Http\Requests;
use Bpocallaghan\Titan\Models\FAQ;
use Bpocallaghan\Titan\Models\FeedbackPurchase;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\FeedbackArtwork;
use Bpocallaghan\Titan\Models\FeedbackGigapan;
use Bpocallaghan\Titan\Models\FeedbackPackage;
use Bpocallaghan\Titan\Models\FeedbackContactUs;
use Bpocallaghan\Titan\Models\FeedbackWeddingPackage;
use Titan\Controllers\TitanAdminController;

class SummaryController extends TitanAdminController
{
    public function index()
    {
        $items = $this->getData();

        return $this->view('reports.summary', compact('items'));
    }

    private function getData()
    {
        $result = [];

        $result[] = ['', ''];
        $result[] = ['<strong>Feedback Forms</strong>', ''];
        $result[] = ['Contact Us', FeedbackContactUs::count()];

        $result[] = ['', ''];
        $result[] = ['<strong>Item 2</strong>', ''];
        $result[] = ['Total xx', '0'];

        return $result;
    }
}