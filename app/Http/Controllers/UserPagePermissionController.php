<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;
use Session;

use App\Http\Helpers\UserHelper;

class UserPagePermissionController extends Controller
{
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
