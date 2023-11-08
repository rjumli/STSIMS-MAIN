<?php

namespace App\Http\Traits\Qualifiers;

use App\Models\Endorsement;
use App\Models\Qualifier;
use App\Models\QualifierAddress;
use App\Models\QualifierProfile;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\Qualifier\IndexResource;
use App\Http\Resources\Qualifier\EndorsementResource;

trait Viewing { 
    
    public function lists($request){
        $bearer = $request->bearerToken();
        if($bearer){
            $token = PersonalAccessToken::findToken($bearer);
            $region = $token->tokenable->profile->agency->region_code;
        }else{
            $region = null;
        }

        $info = (!empty(json_decode($request->info))) ? json_decode($request->info) : NULL;
        $location = (!empty(json_decode($request->location))) ? json_decode($request->location) : NULL;
        $keyword = $info->keyword;

        $data = IndexResource::collection(
            Qualifier::
            with('address.region','address.province','address.municipality','address.barangay')
            ->with('profile')
            ->with('program:id,name','subprogram:id,name','type','status:id,name,type,color,others')
            ->whereHas('profile',function ($query) use ($keyword) {
                $query->when($keyword, function ($query, $keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', '%'.$keyword.'%')
                    ->where(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', '%'.$keyword.'%')
                    ->orWhere('spas_id','LIKE','%'.$keyword.'%');
                });
            })
            ->whereHas('address',function ($query) use ($location) {
                if(!empty($location)){
                    (property_exists($location, 'region')) ? $query->where('region_code',$location->region)->where('is_permanent',1) : '';
                    (property_exists($location, 'province')) ? $query->where('province_code',$location->province)->where('is_permanent',1) : '';
                    (property_exists($location, 'municipality')) ? $query->where('municipality_code',$location->municipality)->where('is_permanent',1) : '';
                    (property_exists($location, 'barangay')) ? $query->where('barangay_code',$location->barangay)->where('is_permanent',1) : '';
                }
            })
            ->where(function ($query) use ($info) {
                ($info->program == null) ? '' : $query->where('program_id',$info->program);
                // ($filter != null) ? ($filter->subprogram == null) ? '' : $query->where('subprogram_id',$filter->subprogram) : '';
                ($info->category == null) ? '' : $query->where('category_id',$info->category);
                ($info->status == null) ? '' : $query->where('status_id',$info->status);
                ($info->year == null) ? '' : $query->where('qualified_year',$info->year);
                ($info->type == null) ? '' : $query->where('status_type',$info->type);
            })
            ->when($region, function ($query, $region) {
                $query->whereHas('address',function ($query) use ($region) {
                    $query->where('region_code',$region); 
                });
            })
            ->paginate($info->counts)
            ->withQueryString()
        );

        return $data;
    }

    public function statistics($request){
        return [
            'statuses' => $this->statuses($request),
            'types' => $this->types($request),
            'active' => [],
            'sync' => 0,
        ];
    } 

    public static function statuses($request){
        $array = [
            Scholar::whereHas('status',function ($query) {
                $query->where('type','ongoing');
            })->count(),
            Scholar::whereHas('status',function ($query) {
                $query->where('name','Graduated');
            })->count(),
            Scholar::count()
        ];
        return $array;
    }

    public static function types($request){
        $array = [
            Scholar::where('is_undergrad',1)->count(),
            Scholar::where('is_undergrad',0)->count(),
        ];
        return $array;
    }

    public function insights($request){

        $bearer = $request->bearerToken();
        $token = PersonalAccessToken::findToken($bearer);
        $code = $token->tokenable->profile->agency->region_code;


        $array = [];
        $data = Qualifier::select('qualified_year AS x',\DB::raw('count(*) AS y'))
        ->when($code, function ($query, $code) {
            $query->whereHas('address',function ($query) use ($code) {
                $query->where('region_code',$code);
            });
        })
        ->orderBy('qualified_year')->groupBy('qualified_year')->get();
        $len = count($data);
        
        $arr = [
            'name' => 'Qualifiers',
            'data' => $data
        ];
        array_push($array,$arr);

        $statistics = [
            Qualifier::where('status_type',14)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            Qualifier::where('status_type',15)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            Qualifier::where('status_type',16)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            Qualifier::where('status_type',17)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count()
        ];

        $statistics2 = [
            Qualifier::where('status_id',18)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            Qualifier::where('status_id',19)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            Qualifier::where('status_id',20)->where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count()
        ];

        return $arr = [
            'name' => 'Qualifiers',
            'icon' => 'bxs-user-circle',
            'color' => 'danger',
            'series' => $array,
            'number' => ($len != 0 && $len != 1) ? $d = $data[$len-1]['y']-$data[$len-2]['y'] : 0,
            'percent' => ($len != 0 && $len != 1) ? round($d/$data[$len-1]['y']*100) : 0,
            'total' => Qualifier::when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            'current' => Qualifier::where('qualified_year',date('Y'))->when($code, function ($query, $code) {
                $query ->whereHas('address',function ($query) use ($code) {
                    $query->where('region_code',$code);
                });
            })->count(),
            'year' => date('Y'),
            'statistics' => $statistics,
            'statistics2' => $statistics2,
            'endorsements' => EndorsementResource::collection(
                Endorsement::with('school.school','school.assigned','course','endorsedby')
                ->with('qualifier.address.region','qualifier.address.province','qualifier.address.municipality','qualifier.address.barangay')
                ->with('qualifier.profile','qualifier.program','qualifier.subprogram','qualifier.status','qualifier.type')
                ->where('endorsed_to',$code)->where('is_approved',0)->get()
            )
        ];
    }
}