<?php

namespace App\Http\Traits\Schools;

use Hashids\Hashids;
use App\Models\School;
use App\Models\Scholar;
use App\Models\ScholarEducation;
use App\Http\Resources\School\ListResource;

trait Viewing { 
    
    public static function lists($request){
        $data = School::with('class')
        ->with('campuses.grading','campuses.term','campuses.region','campuses.assigned','campuses.province','campuses.municipality','campuses.courses.course')
        ->when($request->keyword, function ($query, $keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        })->paginate($request->counts);

        return ListResource::collection($data);
    }

    public static function view($data){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($data);
        
        $data = School::with('class')
        ->with('campuses.grading','campuses.term','campuses.region','campuses.assigned','campuses.province','campuses.municipality')
        ->where('id',$id)->first();
        
        return inertia('Modules/Schools/Profile/Index',[
            'school' => new ListResource($data)
        ]);
    }

    public function counts($request){
        $code = $request->school_id;
        return [
            $this->scholars($code),
            $this->qualifiers($code),
            $this->graduates($code)
        ];
    } 
    
    public function scholars($code){
        $array = [];
        $data = Scholar::select('awarded_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query->whereHas('education',function ($query) use ($code) {
                $query->where('school_id',$code);
            });
        })
        ->orderBy('awarded_year')->groupBy('awarded_year')->get();
        $len = count($data);
        
        $arr = [
            'name' => 'Scholars',
            'data' => $data
        ];
        array_push($array,$arr);

        return $arr = [
            'name' => 'Scholars',
            'icon' => 'bxs-user-circle',
            'color' => 'danger',
            'series' => $array,
            'number' => ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Scholar::when($code, function ($query, $code) {
                $query->whereHas('education',function ($query) use ($code) {
                    $query->where('school_id',$code);
                });
            })->count(),
        ];
    }

    public function qualifiers($code){
        $array = [];
        $data = [];
        $len = count($data);

        $arr = [
            'name' => 'Qualifiers',
            'data' => $data
        ];
        array_push($array,$arr);
        
        return $arr = [
            'name' => 'Qualifiers',
            'icon' => 'bx-notepad',
            'color' => 'primary',
            'series' => $array,
            'number' =>  ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => 0,
        ];
    }

    public function graduates($code){
        $array = [];
        $data = ScholarEducation::select('graduated_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query ->whereHas('scholar',function ($query) use ($code) {
                $query->whereHas('education',function ($query) use ($code) {
                    $query->where('school_id',$code);
                });
            });
        })
        ->whereNotNull('graduated_year')
        ->orderBy('graduated_year')->groupBy('graduated_year')->get();
        $len = count($data);

        $arr = [
            'name' => 'Graduated',
            'data' => $data
        ];
        array_push($array,$arr);
        
        return $arr = [
            'name' => 'Graduates',
            'icon' => 'bxs-graduation',
            'color' => 'success',
            'series' => $array,
            'number' =>  ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => ScholarEducation::when($code, function ($query, $code) {
                $query ->whereHas('scholar',function ($query) use ($code) {
                    $query->whereHas('education',function ($query) use ($code) {
                        $query->where('school_id',$code);
                    });
                });
            })->whereNotNull('graduated_year')->count(),
        ];
    }
}