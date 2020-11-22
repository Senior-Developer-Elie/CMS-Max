@if( $defaultRow )
    <div class="comp-item default-row">
@else
    <div class="comp-item" data-website-id={{ $website->id }}>
@endif
    <span class="col-8 comp-name">
        <a href="//{{ $defaultRow ? "" : $website->website }}" target="_blank">
        {{ $defaultRow ? "" : $website->website }}
        </a>
    </span>
    <span class="col-4 text-right comp-action">
        <button type="button" class="btn btn-default edit-website-btn">
            <i class="fa fa-edit"></i> edit
        </button>
        @if( Auth::user()->can('delete ability') )
        <button type="button" class="btn btn-default delete-website-btn">
            <i class="fa fa-trash delete-icon"> </i>
        </button>
        @endif
    </span>

    <div class="clearfix"></div>
</div>
