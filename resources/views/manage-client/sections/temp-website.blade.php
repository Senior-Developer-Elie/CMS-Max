
<div class="card card-default box-solid">
    <div class="card-header with-border">
        <h3 class="card-title">
            Websites : {{ count($client->websites()->get()) }}
        </h3>
        <div class="card-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body form-horizontal client-add-edit-wrapper">
        @if( isset($client) )
            <div class="col-12">
                <div class="website-list-wrapper table-col">
                    <button type="button" id="add-website-button" class="btn btn-success btn-sm btn-flat pull-right"><i class="fa fa-plus">&nbsp;&nbsp;Add Website</i></button>
                    <div class="tabel-head">
                        <span class="col-8">Url</span>
                        <span class="col-4 text-right">Action</span>
                        <div class="clearfix"></div>
                    </div>
                    @foreach ($client->websites()->orderBy('website')->get() as $website)
                        @include("manage-client.sections.website-row", ["defaultRow" => false])
                    @endforeach
                    <div class="website-list-note">
                        <i class="fa fa-info-circle"></i> Changes to websites are reflected automatically without clicking Confirm button.
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
