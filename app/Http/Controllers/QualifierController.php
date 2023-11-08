<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Qualifiers\Api;
use App\Http\Traits\Qualifiers\Save;
use App\Http\Traits\Qualifiers\Viewing;
use App\Http\Traits\Qualifiers\Truncate;

class QualifierController extends Controller
{
    use Api, Save, Truncate, Viewing;

    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'lists':
               return $this->lists($request);
            break;
            case 'insights':
                return $this->insights($request);
            break;
            default : 
            return inertia('Modules/Qualifiers/Index');
        }
    }

    public function store(Request $request){
        switch($request->type){
            case 'truncate':
                return $this->truncate($request);
             break;
            case 'preview':
                return $this->preview($request);
            break;
            case 'import':
                return $this->import($request);
            break;
            case 'enroll':
                return $this->enroll($request);
            break;
            case 'endorse':
                return $this->endorse($request);
            break;
            case 'endorsed':
                return $this->endorsed($request);
            break;
            case 'update':
                return $this->update($request);
            break;
        }
    }
}
