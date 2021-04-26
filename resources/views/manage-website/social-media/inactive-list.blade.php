@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <table id="inactive-websites-list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Url</th>
                                <th width="150px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $websites as $website )
                                <tr>
                                    <td>
                                        <a href="{{ route('websites.edit', $website) }}">
                                            {{ $website->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="//{{ $website->website }}}">
                                            {{ $website->website }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary mark-as-active-button" data-website-id="{{ $website->id }}">Mark as Active</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('manage-website.social-media.modals.mark-as-active')
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/website/social-media-inactive-list.js?v=2') }}"></script>
    <script src="{{ asset('assets/js/website/social-media-filter.js') }}"></script>
@endsection