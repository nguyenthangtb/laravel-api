<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;

use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;


class UserController extends BaseController
{

    protected $_PER_PAGE = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate($this->_PER_PAGE);

        return UserResource::collection($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create($input);
        return $this->sendResponse($user->toArray(), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if(empty($user)){
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = $input['password'];
        $user->desc = $input['desc'];
        $user->ids_device = $input['ids_device'];
        $user->ids_organization = $input['ids_organization'];

        $user->save();
        
        return $this->sendResponse($user->toArray(), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */

    public function destroy(User $user)
    {
        $user->delete();

        return $this->sendResponse($user->toArray(), 'User deleted successfully.');
    }
}
