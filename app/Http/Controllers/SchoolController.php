<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\School;
use App\Http\Resources\School\ListResource;
use App\Http\Traits\Schools\Save;
use App\Http\Traits\Schools\Viewing;
use Illuminate\Http\Request;
use App\Http\Requests\SchoolRequest;

class SchoolController extends Controller
{
    use Save, Viewing;

    public function index(Request $request){
        switch($request->type){
            case 'lists':
                return $this->lists($request);
            break;
            case 'counts':
                return $this->counts($request);
            break;
            default :
            return inertia('Modules/Schools/Index');
        }
    }

    public function store(SchoolRequest $request){
        switch($request->type){
            case 'school':
                return $this->school($request);
            break;
            case 'campus':
                return $this->campus($request);
            break;
            case 'course':
                return $this->course($request);
            break;
        }
    }

    public function show($data){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($data);
        
        $data = School::with('class')
        ->with('campuses.grading','campuses.term','campuses.region','campuses.assigned','campuses.province','campuses.municipality')
        ->where('id',$id)->first();
        
        return inertia('Modules/Schools/Profile/Index',[
            'school' => new ListResource($data)
        ]);
    }

    public function api(){
        $data = School::with('campuses','campuses.courses')->get();
        return $data;
    }

}
