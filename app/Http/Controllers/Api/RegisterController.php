<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sport;


class RegisterController extends Controller
{

    //Sign-up new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'google_id' => 'required',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'age' => 'required|numeric',
            'sports' => 'required|array|in:1,2,3',
            'describes_you' => 'required',
            'allow_notification' => 'required|boolean',
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(["success"=>false,'errors'=>$validator->errors()], 403);            
        }
        $input = $request->all(); 
        
        $user = $this->create($input); 

        $data = [];
        $sports = [];
        $data['id'] = $user->id;
        $data['google_id'] = $user->google_id;
        $data['email'] = $user->email;
        $data['gender'] = $user->gender;
        $data['age'] = $user->age;
        $arraySports = explode(',', $user->sports_id);
        foreach($arraySports as $k=>$sport)
        {
            $SportsData = Sport::where('id',$sport)->first();
            $sports[$k]['sports_id'] = $SportsData->id;
            $sports[$k]['sports_name'] = $SportsData->sports_name;
        }
        $data['sports'] = $sports;
        $data['describes_you'] = $user->describes_you;
        $data['allow_notification'] = $user->allow_notification;

        return response()->json(["success" => 1, "message" => 'Successfully Login.' ,"data"=> $data], 200);
    }

    // insert data of new user
    protected function create(array $data)
    {

        $user = User::create([
            'google_id' => $data['google_id'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'age' => $data['age'],
            'sports_id' => implode(", ", $data['sports']),
            'describes_you' => $data['describes_you'],
            'allow_notification' => $data['allow_notification'],

        ]);

        return $user; 
    }

    //get all sports
    public function sports()
    {
        $sports = Sport::get();
        $data =[];
        foreach($sports as $k=>$sport)
        {
            $data[$k]['sports_id'] = $sport->id;
            $data[$k]['sports_name'] = $sport->sports_name;
        }
        return response()->json(["success" => 1, "message" => '' ,"data"=> $data], 200);

    }
}
