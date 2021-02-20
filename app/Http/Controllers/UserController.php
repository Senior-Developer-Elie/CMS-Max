<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use App\Sanitizers\UserSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Validators\UserValidator;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    protected $data = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['currentSection'] = 'manage-users';
    }

    /**
     * Website List
     */
    public function index(Request $request)
    {
        $this->data['users'] = User::orderBy('name')->get();
        $this->data['permissions'] = Permission::get();

        return view('users.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->data['permissions'] = Permission::get();

        return view('users.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(Request $request)
    {
        $data = (new UserSanitizer())->sanitize($request->all());
        $validator = new UserValidator();
        if (! $validator->validate($data, 'create')) {
            return redirect()->back()->withInput($data)->withErrors($validator->getErrors());
        }

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        //Assign Admin Role
        $user->assignRole('admin');

        //Assign Permissions
        if( is_array($request->permissions) ) {
            $user->grantPermissions($request->permissions);
        }

        Session::flash('message', 'Admin added successfully!');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('users.edit', [$user->id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param User $user
     * 
     * @return Response
     */
    public function edit(User $user)
    {        
        $this->data['user'] = $user;
        $this->data['permissions'] = Permission::get();
        $this->data['pagePermissions'] = UserHelper::getAllPagePermissions();

        return view('users.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $user
     * @return Response
     */
    public function update(User $user, Request $request)
    {
        $data = (new UserSanitizer())->sanitize($request->all());

        $validator = new UserValidator();
        $validator->ignoreIdForUniqueRule('update', 'email', $user->id);
        if (! $validator->validate($data, 'update')) {
            return redirect()->back()->withInput($data)->withErrors($validator->getErrors());
        }

        if (! empty($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data);
        $user->save();

        //Assign Permissions
        if( is_array($request->permissions) ) {
            $user->grantPermissions($request->permissions);
        }

        Session::flash('message', 'Admin updated successfully!');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('users.index');
    }

    /**
     * Show the page for confirming delete
     *
     * @param $userId
     * @return \Illuminate\View\View
     */
    public function confirmDelete($userId)
    {
        $user = User::findOrFail($userId);

        $this->data['user'] = $user;

        return view('users.confirm-delete', $this->data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $website
     * @return Response
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);

        $user->delete();

        Session::flash('message', 'user deleted successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('users.index');
    }
}
