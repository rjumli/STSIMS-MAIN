<?php

namespace App\Http\Traits\Scholars;

use App\Models\Scholar;
use App\Models\ScholarProfile;
use App\Imports\ScholarsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\Scholar\IndexResource;

trait Bank { 
    
    public function bank($request){
        $data =  Excel::toCollection(new ScholarsImport,$request->import_file);
        $rows = $data[0]; 

        foreach($rows as $row){ 
            if($row[1] != ''){
                $information[] = [
                    'account_no' => $row[0],
                    'lastname' => strtoupper(strtolower($row[1])),
                    'firstname' => strtoupper(strtolower($row[2])),
                ];
            }
        }
        return $information;
    }

    public function bank_update($request){
        set_time_limit(0);
        $result = \DB::transaction(function () use ($request){
            $lists = $request->lists;
            $success = array();
            $failed = array();
            $duplicate = array();
            
            foreach($lists as $list){
                $account_no = $list['account_no'];
                $firstname = $list['firstname'];
                $lastname = $list['lastname'];

                
                $scholar = ScholarProfile::where('firstname',$firstname)->where('lastname',$lastname)->first();
                if($scholar){
                    $count = Scholar::where('account_no',$account_no)->count();
                    if($count == 0){
                        $isko = Scholar::where('id',$scholar->scholar_id)->update(['account_no' => $account_no, 'is_completed' => 1]);
                        if($isko){
                            array_push($success,$account_no);
                        }else{
                            array_push($failed,$account_no);
                        }
                    }else{
                        array_push($duplicate,$account_no);
                    }
                }else{
                    array_push($failed,$account_no);
                }
            }
            $result = [
                'success' => $success,
                'failed' => $failed,
                'duplicate' => $duplicate,
            ];
            return $result;
        });
        return $result;
    }
}