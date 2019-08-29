<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;

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
        $this->validate($request,[
            'name'      => 'required|string|max:191',
            'email'     => 'required|string|email|max:191|unique:users',
            'password'  => 'required|string|min:6',
            'type'      => 'required|string|in:admin,author,user'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'password' => Hash::make($request['password'])
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
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name'      => 'required|string|max:191',
            'email'     => 'required|string|email|max:191|unique:users,email,'.$id,
            'password'  => 'sometimes|min:6',
            'type'      => 'required|string|in:admin,author,user'
        ]);

        if(!empty($request->password))
        {
            $request->merge(['password' => Hash::make($request->password)]);
        }
        $user->update($request->all());

        return ['message' => 'user updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' => 'deleted successfully'];
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
            'name'      => 'required|string|max:191',
            'email'     => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'  => 'sometimes|min:6'
        ]);

        if($request->photo != $currentPhoto)
        {
            $extension = explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            $name = time().".".$extension;
            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            $photoPath = public_path('img/profile/').$currentPhoto;
            if(file_exists($photoPath))
            {
                @unlink($photoPath);
            }
        }

        if(!empty($request->password))
        {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $user->update($request->all());

        return ['message' => 'updated successfully'];
    }
}
