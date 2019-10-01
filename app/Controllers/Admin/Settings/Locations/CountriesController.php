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

class CountriesController extends AdminController
{
    /**
     * Display a listing of country.
     *
     * @return $this
     */
    public function index()
    {
        save_resource_url();

        $items = Country::with('continent')->get();

        return $this->view('titan::settings.locations.countries.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new country.
     *
     * @return $this
     */
    public function create()
    {
        $continents = Continent::getAllList();

        return $this->view('titan::settings.locations.countries.create_edit')
            ->with('continents', $continents);
    }

    /**
     * Store a newly created country in storage.
     *
     * @return $this
     */
    public function store()
    {
        $attributes = request()->validate(Country::$rules, Country::$messages);

        $country = $this->createEntry(Country::class, $attributes);

        return redirect_to_resource();
    }

    /**
     * Display the specified country.
     *
     * @param Country $country
     * @return $this
     */
    public function show(Country $country)
    {
        return $this->view('titan::settings.locations.countries.show')->with('item', $country);
    }

    /**
     * Show the form for editing the specified country.
     *
     * @param Country $country
     * @return $this
     */
    public function edit(Country $country)
    {
        $continents = Continent::getAllList();

        return $this->view('titan::settings.locations.countries.create_edit')
            ->with('item', $country)
            ->with('continents', $continents);
    }

    /**
     * Update the specified country in storage.
     *
     * @param Country $country
     * @return $this
     */
    public function update(Country $country)
    {
        $attributes = request()->validate(Country::$rules, Country::$messages);

        $country = $this->updateEntry($country, $attributes);

        return redirect_to_resource();
    }

    /**
     * Remove the specified country from storage.
     *
     * @param Country $country
     * @return $this
     */
    public function destroy(Country $country)
    {
        $this->deleteEntry($country, request());

        return redirect_to_resource();
    }
}
