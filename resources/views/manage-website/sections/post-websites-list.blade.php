<table id = "{{ $tableId }}" class="table table-bordered table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Website Url</th>
            @foreach ( $allPostLiveOptions as $key => $option )
            <th>
                {{ $option }}
            </th>
            @endforeach
            @if( $tableId == 'active-websites-table' )
                <th>
                </th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ( $websites as $website )
            <tr data-website-id="{{ $website->id }}">
                <td>
                    {{ $website->website }}
                </td>
                @foreach ( $allPostLiveOptions as $key => $option )
                    <td class="text-center option-cell" data-option-value="{{ $key }}">
                        <a href="#" class="option-value" data-value="{{ $website->post_live[$key] ?? 'no' }}"></a>
                    </td>
                @endforeach
                @if( $tableId == 'active-websites-table' )
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning archive-website-btn">Mark as Completed</button>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
