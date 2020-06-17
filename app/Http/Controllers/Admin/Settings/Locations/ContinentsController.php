<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Settings\Locations;

use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\City;
use Bpocallaghan\Titan\Models\Suburb;
use Bpocallaghan\Titan\Models\Country;
use Bpocallaghan\Titan\Models\Province;
use Bpocallaghan\Titan\Models\Continent;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class ContinentsController extends AdminController
{
	/**
	 * Display a listing of continent.
	 *
	 * @return $this
	 */
	public function index()
	{
		save_resource_url();

		return $this->view('titan::settings.locations.continents.index')->with('items', Continent::all());
	}

	/**
	 * Show the form for creating a new continent.
	 *
	 * @return $this
	 */
	public function create()
	{
		return $this->view('titan::settings.locations.continents.create_edit');
	}

	/**
	 * Store a newly created continent in storage.
	 *
	 * @return $this
	 */
	public function store()
	{
		$attributes = request()->validate(Continent::$rules, Continent::$messages);

        $continent = $this->createEntry(Continent::class, $attributes);

        return redirect_to_resource();
	}

	/**
	 * Display the specified continent.
	 *
	 * @param Continent $continent
	 * @return $this
	 */
	public function show(Continent $continent)
	{
		return $this->view('titan::settings.locations.continents.show')->with('item', $continent);
	}

	/**
	 * Show the form for editing the specified continent.
	 *
	 * @param Continent $continent
     * @return $this
     */
    public function edit(Continent $continent)
	{
		return $this->view('titan::settings.locations.continents.create_edit')->with('item', $continent);
	}

	/**
	 * Update the specified continent in storage.
	 *
	 * @param Continent  $continent
     * @return $this
     */
    public function update(Continent $continent)
	{
		$attributes = request()->validate(Continent::$rules, Continent::$messages);

        $continent = $this->updateEntry($continent, $attributes);

        return redirect_to_resource();
	}

	/**
	 * Remove the specified continent from storage.
	 *
	 * @param Continent  $continent
	 * @return $this
	 */
	public function destroy(Continent $continent)
	{
		$this->deleteEntry($continent, request());

        return redirect_to_resource();
	}
}
