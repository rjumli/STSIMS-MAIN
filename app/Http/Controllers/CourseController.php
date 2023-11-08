<?php

namespace App\Http\Controllers;

use App\Models\ListCourse;
use Illuminate\Http\Request;
use App\Http\Traits\Courses\Save;
use App\Http\Resources\DefaultResource;

class CourseController extends Controller
{
    use Save;

    public function index(Request $request){
        switch($request->type){
            case 'lists':
                return $this->lists($request);
            break;
            default :
            return inertia('Modules/Courses/Index');
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
        }
    }

    public function lists($request){
        $data = ListCourse::when($request->keyword, function ($query, $keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        })->paginate($request->counts);
        return DefaultResource::collection($data);
    }

    public function api(){
        $data = ListCourse::all();
        return $data;
    }
}
