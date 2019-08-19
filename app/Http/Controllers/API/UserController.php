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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

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

    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        $currentPhoto = $user->photo;

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:8'
        ]);

        if($request->photo != $currentPhoto) {
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ['message' => 'Profile updated successfully'];
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name'      =>  'required|string|max:191',
            'email'     =>  'required|string|max:191|email|unique:users,email,'.$user->id,
            'password'  =>  'sometimes|string|min:8',
        ]);
        
        $user->update( $request->all() );

        return ['message' => 'User updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = auth('api')->user();
        $user = User::findOrFail($id);

        // return ['message' => $currentUser]; /* this returns null! */

        if($currentUser->id == $user->id) {

            return response()->json(['message' => 'can not delete the current user'], 403);

        } else {
            $user->delete();
        
            return ['message' => 'User deleted successfuly'];
        }

        // $user->delete();
        // return ['message' => 'User deleted successfully'];
    }
}
