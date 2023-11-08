<?php

namespace App\Http\Controllers;

use App\Models\ListAgency;
use App\Models\ListDropdown;
use App\Models\ListPrivilege;
use App\Models\ListProgram;
use App\Models\ListStatus;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Models\LocationBarangay;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;

class ListController extends Controller
{
    public function regions()
    {
        $data = LocationRegion::orderBy('id','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function provinces($id = null)
    {
        $data = LocationProvince::where('region_code',$id)->orderBy('name','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function municipalities($id = null)
    {
        $data = LocationMunicipality::where('province_code',$id)->orderBy('name','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function barangays($id = null)
    {
        $data = LocationBarangay::where('municipality_code',$id)->orderBy('name','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function api_agencies(){
        $data = ListAgency::all();
        return $data;
    }

    public function api_dropdowns(){
        $data = ListDropdown::all();
        return $data;
    }

    public function api_privileges(){
        $data = ListPrivilege::all();
        return $data;
    }

    public function api_programs(){
        $data = ListProgram::all();
        return $data;
    }

    public function api_statuses(){
        $data = ListStatus::all();
        return $data;
    }

    public function api_location($type)
    {   
        switch($type){
            case 'regions' :
                $data = LocationRegion::get();
            break;
            case 'provinces' :
                $data = LocationProvince::get();
            break;
            case 'municipalities' :
                $data = LocationMunicipality::get();
            break;
            case 'barangays' :
                $data = LocationBarangay::get();
            break;
        }
        return $data;
    }
}
