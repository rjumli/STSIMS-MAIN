<?php

namespace App\Http\Traits\Schools;

use App\Models\School;
use App\Models\SchoolCampus;
use App\Models\SchoolCourse;
use App\Http\Resources\School\ListResource;
use App\Http\Resources\School\CampusResource;
use App\Http\Resources\School\CourseResource;

trait Save { 
    
    public static function school($request){
        $data = \DB::transaction(function () use ($request){
            $data = School::create($request->all());
            $data = School::find($data->id);
            return $data;
        });

        return back()->with([
            'message' => 'School created successfully. Thanks',
            'data' => new ListResource($data),
            'type' => 'bxs-check-circle',
        ]); 
    }

    public static function campus($request){
        $data = \DB::transaction(function () use ($request){
            $data = SchoolCampus::create($request->all());
            $data = SchoolCampus::find($data->id);
            return $data;
        });

        return back()->with([
            'message' => 'Campus created successfully. Thanks',
            'data' => new CampusResource($data),
            'type' => 'bxs-check-circle',
        ]); 
    }

    public static function course($request){
        $data = \DB::transaction(function () use ($request){
            $data = SchoolCourse::create(array_merge($request->all()));
            $data = SchoolCourse::find($data->id);
            return $data;
        });

        return back()->with([
            'message' => 'Course added successfully. Thanks',
            'data' => new CourseResource($data),
            'type' => 'bxs-check-circle',
        ]); 
    }

}