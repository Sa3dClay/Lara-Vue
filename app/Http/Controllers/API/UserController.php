<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'name'      =>  'required|string|max:191',
            'email'     =>  'required|string|max:191|email|unique:users',
            'password'  =>  'required|string|min:8',
        ]);

        if( empty( $request['bio'] ) ) {
            $request['bio'] = 'no bio';
        }
        if( empty( $request['type'] ) ) {
            $request['type'] = 'user';
        }

        return User::create([
            'name'      => $request['name'],
            'email'     => $request['email'],
            'type'      => $request['type'],
            'bio'       => $request['bio'],
            'password'  => Hash::make($request['password'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        // return ['message' => $currentUser]; /* this returns null! */

        // if($currentUser->id == $user->id) {
        //     return ['message' => `can't delete the current user`];

        // } else {
        //     // $user->delete();
        
        //     return ['message' => 'User deleted successfuly'];
        // }

        $user->delete();
        return ['message' => 'User deleted successfuly'];
    }
}
