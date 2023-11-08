<?php

namespace App\Http\Controllers;

use App\Models\Scholar;
use Illuminate\Http\Request;
use App\Http\Traits\Scholars\Save;
use App\Http\Traits\Scholars\Bank;
use App\Http\Traits\Scholars\Truncate;
use App\Http\Traits\Scholars\Viewing;
use Laravel\Sanctum\PersonalAccessToken;

class ScholarController extends Controller
{
    use Bank, Save, Truncate, Viewing; 

    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'lists':
               return $this->lists($request);
            break;
            case 'statistics':
                return $this->statistics($request);
            break;
            default : 
            return inertia('Modules/Scholars/Index');
        }
    }

    public function store(Request $request){
        switch($request->type){
            case 'preview':
                return $this->preview($request);
            break;
            case 'import':
                return $this->import($request);
            break;
            case 'truncate':
                return $this->truncate($request);
            break;
            case 'sync':
                return $this->sync($request);
            break;
            case 'bank':
                return $this->bank($request);
            break;
            case 'bank-update':
                return $this->bank_update($request);
            break;
        }
    }

    public function api(Request $request){
        $bearer = $request->bearerToken();
        $token = PersonalAccessToken::findToken($bearer);
        $region = $token->tokenable->profile->agency->region_code;
       
        $data = Scholar::with('address')->with('education')->with('profile')
        ->whereHas('education',function ($query) use ($region) {
            $query->whereHas('school',function ($query) use ($region) {
                $query->where('assigned_region',$region); 
            });
        })
        ->get();
        return $data;
    }
}
