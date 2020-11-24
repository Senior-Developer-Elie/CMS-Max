<form role="form" method="POST" action="{{ isset($client) ? url('/edit-blog-client/' . $client->id) : url('/add-client') }}">
    @csrf
    <div class="card card-default box-solid">
        <div class="card-header with-border">
            <h3 class="card-title">
                {{ isset($client) ? "Client: " . $client->name : 'Add Client'}}
            </h3>
            <div class="card-tools pull-right">
                <button type="button"
                        class="btn btn-sm"
                        data-card-widget="collapse"
                        data-toggle="tooltip"
                        title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body form-horizontal client-add-edit-wrapper">
            <div class="row main-info-wrapper">
                <div class="col-6 main-info-wrapper">
                    <div class="form-group">
                        <label for="clientName" class="control-label">Client Name:</label>
                        <div class="">
                            <input type="text" class="form-control" id="clientName" placeholder="Enter client name" name = "name" value="{{ isset($client) ? $client->name : '' }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Contacts:</label>
                        <div class="">
                            <textarea class="form-control pull-right" id="contacts" name = "contacts">{{ isset($client) ? $client->contacts : '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Notes:</label>
                        <div class="notes-wrapper">
                            <textarea class="form-control pull-right" id="notes" name = "notes">{{ isset($client) ? $client->notes : '' }}</textarea>
                        </div>
                    </div>

                    @if (isset($client))
                        <div class="form-group">
                            <label for="restore" class="text-bold mb-1">Restore Notes From Previous Save</label>
                            <div class="row">
                                <div class="col-sm-10">
                                    <select name="restore" class="form-control">
                                        @foreach ($client->notesVersions()->latest()->get() as $notesVersion)
                                            <option value="{{ $notesVersion->id }}">
                                                {{ \Carbon\Carbon::parse($notesVersion->created_at)->format('M d, Y g:i:s A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <br class="visible-xs">
                                <div class="col-sm-2">
                                    <button id="restore-button" type="button" class="btn btn-block btn-default">Restore</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
                @can('content manager')
                    @if( isset($client) && Auth::user()->can('delete ability') )
                        <a href="{{ url('delete-client/' . $client->id) }}">
                            <button type="button" class="btn btn-danger pull-left" style="margin-right: 15px">Remove Client</button>
                        </a>
                        @if( $client->archived )
                            <a href="{{ url('un-archive-client/' . $client->id) }}">
                                <button type="button" class="btn btn-info pull-left">Re-enable Client</button>
                            </a>
                        @else
                            <a href="{{ url('archive-client/' . $client->id) }}">
                                <button type="button" class="btn btn-warning pull-left">Archive Client</button>
                            </a>
                        @endif
                    @endif
                    <button type="submit" class="btn btn-primary pull-right">Confirm</button>
                    @if( isset($client) && !is_null($client->api_id) && $client->api_id > 0 )
                        <button id = "sync-client-btn" type="button" class="btn btn-info pull-right" style="margin-right: 15px"
                            data-toggle="tooltip" data-html="true" data-placement="top" title="Last Synced At<br> {{ (new \Carbon\Carbon($client->synced_at))->format('m/d/Y h:i a') }}">
                            Sync From API
                        </button>
                    @endif
            @endcan
        </div>
    </div>
</form>
