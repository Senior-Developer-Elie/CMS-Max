<table id = "cms-maxs-list-table" class="table table-bordered table-striped" style="width:100%">
        <thead>
            <tr>
                <th width="200px">Website</th>
                <th>Chargebee</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($websites as $website)
                <tr data-website-id={{ $website->id }}>
                    <td class="website-url-wrapper">
                        <a href="{{ route('websites.edit', $website) }}" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                            {{ $website->website }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('client-history?clientId=' . $website->client()->id) }}">
                            {{ $website->name }}
                            @if( Auth::user()->hasRole('super admin') && $website->sync_from_client )
                                <i class="fas fa-sync ml-1" style="font-size: 12px"></i>
                            @endif
                        </a>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>