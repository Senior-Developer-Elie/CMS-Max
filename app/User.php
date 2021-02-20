<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

use App\AdminHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    const USER_TYPE_EMPLOYEE = 'employee';
    const USER_TYPE_CONTRACTOR = 'contractor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'page_permissions', 'type', 'job_roles', 'email_notification_enabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'page_permissions'  => 'array',
    ];

    protected static $types = [
        self::USER_TYPE_EMPLOYEE => 'Employee',
        self::USER_TYPE_CONTRACTOR => 'Contractor',
    ];

    // event handler
    public static function boot() {
        parent::boot();

        static::deleting(function($user) {

            //Remove file from storage
            if( !is_null($user->avatar) && $user->avatar !== '' && Storage::disk('s3')->exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
        });

        User::updating(function($user)
        {
            $original = $user->getOriginal();
            if ($user->avatar != $original['avatar']) {
                if( !is_null($original['avatar']) && $original['avatar'] !== '' && Storage::disk('s3')->exists($original['avatar'] )) {
                    Storage::delete($original['avatar']);
                }
            }
        });
    }

    /**
     * Get Last Activity Object
     */
    public function last_activity() {
        return AdminHistory::where('user_id', $this->id)->orderByDesc('created_at')->first();
    }

    /**
     * Get Writers
     */
    public static function writers() {
        $writers = [];
        foreach( self::get() as $user ){
            if( !$user->hasRole('super admin') && $user->can('writer') )
                $writers[] = $user;
        }
        return $writers;
    }

    /**
     * Get User Job Title
     */
    public function job_title() {

        $user = Auth::user();

        if( $user->hasRole('super admin') )
            return 'Super Admin';
        if( $user->can('google ads') )
            return 'Google Ads';
        if( $user->can('content manager') )
            return 'Content Manager';
        if( $user->can('writer') )
            return 'Writer';
        if( $user->can('inner pages') )
            return 'Jobs To Do Manager';
        return 'Member Of Evolution Marketing';
    }

    public function getPublicAvatarLink()
    {
        if( !is_null($this->avatar) && $this->avatar !== '' && Storage::disk('s3')->exists($this->avatar))
            return Storage::url($this->avatar);
        else
            return asset("assets/images/default-avatar.jpg");
    }

    public function getInitials()
    {
        $str = $this->name;
        $ret = '';
        foreach (explode(' ', $str) as $word)
            $ret .= strtoupper($word[0]);
        return $ret;
    }

    public function hasPagePermission($permission)
    {
        if( $this->hasRole('super admin') )
            return true;
        if( is_array($this->page_permissions) && in_array($permission, $this->page_permissions))
            return true;
        return false;
    }

    public function getRoleNamesAttribute()
    {
        return $this->getRoleNames()->toArray();
    }

    public function getPermissionNamesAttribute()
    {
        return array_column($this->getAllPermissions()->toArray(), 'name');
    }

    public function grantPermissions(array $permissionNames)
    {
        foreach ($permissionNames as $permissionName) {
            $this->givePermissionTo($permissionName);
        }
    }

    public function clientLeads()
    {
        return $this->hasMany(Client::class, 'client_lead');
    }

    public function projectManagers()
    {
        return $this->hasMany(Client::class, 'project_manager');
    }

    /**
     * Get types
     *
     * @return array
     */
    public static function types()
    {
        return static::$types;
    }
}
