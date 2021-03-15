<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Task;
use App\Stage;
use App\User;
use App\Client;

use Auth;

use App\Http\Helpers\TaskHelper;

class WebsiteProgressController extends Controller
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
     * Website Permission Index
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Websites in Progress') )
            return redirect('/webadmin');

        $stages = Stage::orderBy('priority')->get();
        $users = User::where('type', '!=', User::USER_TYPE_EMPLOYEE)
            ->orderBy('name')
            ->get();

        $prettyUsers = [];
        foreach( $users as $user ){
            $prettyUser = $user->toArray();
            $prettyUser['initials'] = $user->getInitials();
            $prettyUser['public_avatar_link'] = $user->getPublicAvatarLink();
            $prettyUser['value'] = $user->id;
            $prettyUser['text'] = $user->name;
            $prettyUsers[] = $prettyUser;
        }

        $allMailHosts = TaskHelper::getAllEmailHost();
        $prettyMailHosts = [];
        foreach( $allMailHosts as $index => $mail_host ) {
            $prettyMailHosts[] = [
                'value' => $index,
                'text'  => $mail_host
            ];
        }

        $clients = Client::orderBy('name')->get();
        $prettyClients = [];
        foreach( $clients as $client ){
            //if( Task::where('client_id', $client->id)->count() == 0 ){
                $prettyClients[] = [
                    'value' => $client->id,
                    'text'  => $client->name
                ];
            //}
        }

        $taskTypeFilter = is_null($request->input('taskTypeFilter')) ? 'active' : $request->input('taskTypeFilter');
        //Total task count except for collections
        if( $taskTypeFilter == 'active' )
            $totalTaskCount = count(Task::where("stage_id", "!=", 9)->where('stage_id', '!=', 10)->get());
        else
            $totalTaskCount = count(Task::where('stage_id', 10)->get());

        return view('website-progress.index', [
            'stages'                => $stages,
            'currentSection'        => 'website-progress',
            'taskTypeFilter'        => $taskTypeFilter,
            'allUsers'              => $prettyUsers,
            'allMailHosts'          => $prettyMailHosts,
            'activeTaskId'          => is_null($request->input('activeTaskId')) ? -1 : $request->input('activeTaskId'),
            'isUniqueView'          => is_null($request->input('isUniqueView')) ? false : ($request->input('isUniqueView') == "false" ? false : true),
            'totalTaskCount'        => $totalTaskCount,
            'allPreLiveOptions'     => TaskHelper::getAllPreLiveOptions(),
            'allClients'            => $prettyClients
        ]);
    }

    public function updateStagePriorities(Request $request)
    {
        $priorities = $request->input('priorities');
        foreach( $priorities as $priority ) {
            $stage = Stage::find($priority['stageId']);
            if( !is_null($stage) ) {
                $stage->priority = $priority['priority'];
                $stage->save();
            }
        }
        return response()->json([
            'status'    => 'success'
        ]);
    }
}
