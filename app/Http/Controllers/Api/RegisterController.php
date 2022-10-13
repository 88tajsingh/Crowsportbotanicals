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
            'sports' => 'required',
            'sports.*.sports_id' => 'required|numeric|in:1,2,3',
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
        foreach($user->getsports as $getsport)
        {
            $sports['sports_id'] = $getsport->sports_id;
            $sports['sports_name'] = $getsport->sports_name;
        }
        $data['sports'] =$sports;
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
            'describes_you' => $data['describes_you'],
            'allow_notification' => $data['allow_notification'],

        ]);
        foreach($data['sports'] as $sport)
        {
            Sport::create([
                'user_id' => $user->id,
                'sports_id'=> $sport['sports_id'],
                'sports_name'=> $sport['sports_name'],
                'status'=> 1,
            ]);
        }

        return $user; 
    }

    //get all sports
    public function sports()
    {
        $sports = Sport::get();
        $data =[];
        foreach($sports as $sport)
        {
            $data['sports_id'] = $sport->sports_id;
            $data['sports_name'] = $sport->sports_name;
        }
        return response()->json(["success" => 1, "message" => '' ,"data"=> $data], 200);

    }
}
