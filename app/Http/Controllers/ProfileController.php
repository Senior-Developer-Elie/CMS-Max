<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\User;

use Auth;
use Session;
use Carbon;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Profile Page
     */
    public function index(Request $request)
    {
        if( $request->isMethod('get') ) {
            return view('profile.index');
        }
        else if( $request->isMethod('post') ) {

            $user = Auth::user();

            $passwordRule = [];
            if( !is_null($request->password) )
                $passwordRule = ['string', 'min:8', 'confirmed'];
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => $passwordRule,
            ]);
            $validator->validate();

            $user->name = $request->name;
            $user->email = $request->email;

            if( !is_null($request->password) )
                $user->password = Hash::make($request->password);

            $user->save();

            Session::flash('message', 'Profile updated successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/profile');
        }
    }

    /**
     * Upload Avatar
     */
    public function uploadPhoto(Request $request)
    {
        if( $request->hasFile('image') ) {
            $user = Auth::user();
            //$user->removeAvatarFile();
            $user->avatar = request()->file('image')->store('avatars');
            $user->save();

            Session::flash('message', 'Photo updated successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()
            ->json([
                'status'    => 'success'
            ]);
        }
        return response()
            ->json([
                'status'    => 'error'
            ]);
    }
}
