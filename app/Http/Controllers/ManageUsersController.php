<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;
use DateTime;

use App\Http\Helpers\UserHelper;

class ManageUsersController extends Controller
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
     * Manage Default Text
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Manage Admins') )
            return redirect('/webadmin');

        //Get Users
        $users = User::orderBy('created_at')->get();

        $prettyUsers = [];
        foreach( $users as $user ) {
            $roles = $user->getRoleNames()->toArray();
            $permissions = array_column($user->getAllPermissions()->toArray(), 'name');

            $prettyUsers[] = [
                'id'            => $user->id,
                'avatar'        => $user->getPublicAvatarLink(),
                'name'          => $user->name,
                'email'         => $user->email,
                'created_at'    => $user->created_at,
                'roles'         => $roles,
                'permissions'   => $permissions,
                'last_activity' => is_null($user->last_activity()) ? '' : ((new DateTime($user->last_activity()->created_at))->format('M d, Y h:i A')),
            ];
        }

        $data = [
            'currentSection'            => 'manage-users',
            'users'                     => $prettyUsers,
            'allPermissions'            => Permission::get()
        ];

        return view('manage-users.list', $data);
    }

    /**
     * Add/Edit User
     */
    public function addUser(Request $request)
    {
        if( $request->isMethod('get') ){
            $allPermissions = array_column(Permission::get()->toArray(), 'name');
            return view("manage-users.add", [
                'currentSection'    => 'manage-users',
                'allPermissions'    => $allPermissions
            ]);
        }
        else if( $request->isMethod('post') ){

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $validator->validate();

            //Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            //Assign Admin Role
            $user->assignRole('admin');

            //Assign Permissions
            if( is_array($request->permissions) ) {
                foreach( $request->permissions as $permission )
                {
                    $user->givePermissionTo($permission);
                }
            }

            Session::flash('message', 'Admin added successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/manage-admin');
        }
    }

    /**
     * Edit User
     */
    public function editUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if( !$user )
            abort(404);

        $roles = $user->getRoleNames()->toArray();
        $permissions = array_column($user->getAllPermissions()->toArray(), 'name');

        $prettyUser = [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'roles'             => $roles,
            'permissions'       => $permissions,
            'page_permissions'  => $user->page_permissions
        ];

        if( $request->isMethod('get') ){
            $allPermissions = array_column(Permission::get()->toArray(), 'name');

            $data = [
                'currentSection'        => 'manage-users',
                'user'                  => $prettyUser,
                'objectUser'            => $user,
                'allPermissions'        => $allPermissions,
                'allPagePermissions'    => UserHelper::getAllPagePermissions(),
            ];
            return view("manage-users.add", $data);
        }
        if( $request->isMethod('post') ){

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

            $allPermissions = array_column(Permission::get()->toArray(), 'name');
            //Remove All Permissions
            foreach( $allPermissions as $permission ) {
                $user->revokePermissionTo($permission);
            }

            //Assign Permissions
            $permissions = is_null($request->permissions) ? [] : $request->permissions;
            foreach( $permissions as $permission )
            {
                $user->givePermissionTo($permission);
            }
            $user->save();

            Session::flash('message', 'Admin is edited successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/manage-admin');
        }
    }

    /**
     * Edit User Page Permissions
     */
    public function editUserPagePermissions(Request $request, $userId)
    {
        $user = User::find($userId);

        if( !$user )
            abort(404);

        if( $request->isMethod('get') ){

            $data = [
                'currentSection'        => 'manage-users',
                'user'                  => $user,
                'allPagePermissions'    => UserHelper::getAllPagePermissions(),
            ];
            return view("manage-users.edit-page-permission", $data);
        }
    }

    /**
     * Delete User
     */
    public function deleteUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if( !$user )
            abort(404);

        if( $request->isMethod('get') ) {
            return view('manage-users.delete', [
                'currentSection'    => 'manage-users',
                'user'              => $user
            ]);
        }
        else {
            $user->delete();
            Session::flash('message', 'Admin is removed successfully!');
            Session::flash('alert-class', 'alert-success');

            return redirect('/manage-admin');
        }
    }

    /**
     * Get Permission Content
     */
    public function getPermission(Request $request)
    {
        $permission = Permission::find($request->input('permissionId'));
        if( is_null($permission) ) {
            return response()
                ->json([
                    'status'    => 'error'
                ]);
        }
        return response()
            ->json([
                'status'        => 'success',
                'permission'    => $permission->toArray()
            ]);
    }

    /**
     * Update Permission
     */
    public function updatePermission(Request $request)
    {
        $permission = Permission::find($request->input('permissionId'));
        if( is_null($permission) ) {
            return response()
                ->json([
                    'status'    => 'error'
                ]);
        }
        $permission->description = $request->input('description');
        $permission->save();

        Session::flash('message', 'Permission description updated successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()
            ->json([
                'status'        => 'success'
            ]);
    }

    /**
     * Update Page permissions for user
     */
    public function updatePagePermissions(Request $request)
    {
        $user = User::find($request->input('userId'));
        if( is_null($user) )
            return response()->json([
                'status'    => 'error'
            ]);

        $user->page_permissions = $request->input('page_permissions');
        $user->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }
}
