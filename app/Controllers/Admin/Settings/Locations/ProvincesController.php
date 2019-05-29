<?php

namespace Bpocallaghan\Titan\Http\Controllers\Admin\Settings\Locations;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Models\City;
use Bpocallaghan\Titan\Models\Suburb;
use Bpocallaghan\Titan\Models\Country;
use Bpocallaghan\Titan\Models\Province;
use Bpocallaghan\Titan\Models\Continent;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class ProvincesController extends TitanAdminController
{
    /**
     * Display a listing of province.
     *
     * @return Response
     */
    public function index()
    {
        save_resource_url();

        return $this->view('titan::settings.locations.provinces.index')
            ->with('items', Province::all());
    }

    /**
     * Show the form for creating a new province.
     *
     * @return Response
     */
    public function create()
    {
        $countries = Country::getAllList();

        return $this->view('titan::settings.locations.provinces.add_edit')->with('countries', $countries);
    }

    /**
     * Store a newly created province in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Province::$rules, Province::$messages);

        $this->createEntry(Province::class, $request->all());

        return redirect_to_resource();
    }

    /**
     * Display the specified province.
     *
     * @param Province $province
     * @return Response
     */
    public function show(Province $province)
    {
        return $this->view('titan::settings.locations.provinces.show')->with('item', $province);
    }

    /**
     * Show the form for editing the specified province.
     *
     * @param Province $province
     * @return Response
     */
    public function edit(Province $province)
    {
        $countries = Country::getAllList();

        return $this->view('titan::settings.locations.provinces.add_edit')
            ->with('item', $province)
            ->with('countries', $countries);
    }

    /**
     * Update the specified province in storage.
     *
     * @param Province $province
     * @param Request  $request
     * @return Response
     */
    public function update(Province $province, Request $request)
    {
        $this->validate($request, Province::$rules, Province::$messages);

        $this->updateEntry($province, $request->all());

        return redirect_to_resource();
    }

    /**
     * Remove the specified province from storage.
     *
     * @param Province $province
     * @param Request  $request
     * @return Response
     */
    public function destroy(Province $province, Request $request)
    {
        $this->deleteEntry($province, $request);

        return redirect_to_resource();
    }
}
