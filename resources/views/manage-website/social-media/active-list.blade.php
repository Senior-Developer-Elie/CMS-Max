@section('content')
    <div class="social-grid-page">
        <div class="social-grid">
            <div class="social-grid-row social-grid-header-row">
                <div class="social-grid-cell social-grid-name-cell">
                    <strong>Website Name</strong>
                </div>
                <div class="social-grid-cell social-grid-links-cell">
                    <strong>Links</strong>
                </div>
                <div class="social-grid-cell social-grid-assignee-cell">
                    <strong>Assignee</strong>
                </div>
                <div class="social-grid-cell social-grid-reviewer-cell">
                    <strong>Reviewer</strong>
                </div>
                <div class="social-grid-cell social-grid-progress-cell">
                    <strong>Progress</strong>
                </div>
            </div>
            <div class="social-grid-body" class="scroll-bar-wrap">
                <div class="scroll-box">
                    @foreach ( $socialMediaStages as $socialMediaStage )
                        <div class="social-grid-stage-wrapper open" data-stage-id={{ $socialMediaStage->id }}>
                            <div class="social-grid-stage-header">
                                <button class="social-grid-stage-toggle-button">
                                    <svg focusable="false" viewBox="0 0 32 32" class="icon left-triangle-icon"><path d="M7.207,13.707L16.5,23l9.293-9.293c0.63-0.63,0.184-1.707-0.707-1.707H7.914C7.023,12,6.577,13.077,7.207,13.707z"></path></svg>
                                    <svg focusable="false" viewBox="0 0 32 32" class="icon right-triangle-icon"><path d="M13.707,6.707L23,16l-9.293,9.293C13.077,25.923,12,25.477,12,24.586V7.414C12,6.523,13.077,6.077,13.707,6.707z"></path></svg>
                                </button>
                                <div class="social-grid-stage-header-container">
                                    <label class="social-grid-stage-title">{{ $socialMediaStage->name }} (<span class="stage-websites-count">{{ $socialMediaStage->websites->count() }}</span>)</label>
                                </div>
                            </div>
                            <div class="social-grid-stage-body">
                                @foreach ( $socialMediaStage->websites as $website)
                                    @include('manage-website.social-media.website-row', [ 'website' => $website ])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="cover-bar"></div>
            </div>
        </div>
        @include('manage-website.social-media.website-details')
    </div>

    @include('manage-website.social-media.modals.mark-as-inactive')
    @include('manage-website.social-media.modals.total-budget-by-assignee')
@endsection

@section('css')
    @parent

    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/tip-yellowsimple.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/lib/jquery-editable/css/jquery-editable.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/social-media-list.css?v=2') }}">
@endsection
@section('javascript')
    @parent

    <script>
        var socialMediaPlans = {!! json_encode(\App\AngelInvoice::socialPlanProducts()) !!};
        var socialMediaStages = {!! json_encode(\App\SocialMediaStage::orderBy('order')->get()->toArray()) !!};
        var activeWebsiteId = {{ (! empty($activeWebsiteId ?? null)) ? $activeWebsiteId : 0 }};
        var allUsers = {!! json_encode(\App\User::where('type', '!=', \App\User::USER_TYPE_CMS_MAX_DEVELOPER)->orderBy('name')->get()) !!};
    </script>
    <script src="{{ mix('js/datatable.js') }}"></script>

    <script src="{{ asset('assets/lib/jquery-editable/js/jquery.poshytip.js') }}"></script>
    <script src="{{ asset('assets/lib/jquery-editable/js/jquery-editable-poshytip.js') }}"></script>

    <script src="{{ asset('assets/js/website/social-media-filter.js?v=2') }}"></script>
    <script src="{{ asset('assets/js/website/social-media.js?v=23') }}"></script>
@endsection