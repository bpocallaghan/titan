<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin;

use Bpocallaghan\Titan\Http\Requests;
use Illuminate\Http\Request;

class DashboardController extends AdminController
{
	public function index()
	{
		return $this->view('dashboard');
	}
}
