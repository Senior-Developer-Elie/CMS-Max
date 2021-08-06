<div class="social-grid-row website-row" data-website-id = {{ $website->id }}>
    <div class="social-grid-cell social-grid-name-cell">
        {{ $website->name }}

        <span class="website-icons-wrapper">
            @if (! empty($website->merchant_center))
                <a class="website-icon" href="{{ $website->merchant_center }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Merchant Center">
                    <img src="{{ asset('assets/images/merchant-icon.png') }}" />
                </a>
            @endif
            @if (! empty($website->flow_chart))
                <a class="website-icon" href="{{ $website->flow_chart }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Flowchart">
                    <img src="{{ asset('assets/images/flow-chart-icon.png') }}" />
                </a>
            @endif
            @if( !empty($website->uses_our_credit_card) )
                <a class="website-icon" data-toggle="tooltip" data-placement="left" title="Uses our Credit Card">
                    <img src="{{ asset('assets/images/dollar-icon.png') }}" />
                </a>
            @endif
            @if( !empty($website->drive) )
                <a class="website-icon" href = "{{ $website->drive }}" target="_blank" data-toggle="tooltip" data-placement="left" title="Google Drive">
                    <img src="{{ asset('assets/images/google-drive-icon.png') }}" />
                </a>
            @endif
            @if( !empty($website->social_calendar) )
                <a class="website-icon" href = "{{ $website->social_calendar }}" target="_blank" data-toggle="tooltip" data-placement="left" title="Social Calendar">
                    <img src="{{ asset('assets/images/social-calendar-icon.png') }}" />
                </a>
            @else
                <a class="website-icon" data-toggle="tooltip" data-placement="left" title="No Social Calendar">
                    <img src="{{ asset('assets/images/social-calendar-disabled-icon.png') }}" />
                </a>
            @endif
        </span>
    </div>
    <div class="social-grid-cell social-grid-links-cell">
        @if (! empty($website->linkedin_url))
            <a href="{{ $website->linkedin_url }}" target="_blank" class="social-icon" data-field-name="linkedin_url">
                <img src="{{ asset('assets/images/social-linkedin.png') }}">
            </a>
        @endif

        @if (! empty($website->youtube_url))
            <a href="{{ $website->youtube_url }}" target="_blank" class="social-icon" data-field-name="youtube_url">
                <img src="{{ asset('assets/images/social-youtube.png') }}">
            </a>
        @endif

        @if (! empty($website->twitter_url))
            <a href="{{ $website->twitter_url }}" target="_blank" class="social-icon" data-field-name="twitter_url">
                <img src="{{ asset('assets/images/social-twitter.png') }}">
            </a>
        @endif

        @if (! empty($website->facebook_url))
            <a href="{{ $website->facebook_url }}" target="_blank" class="social-icon" data-field-name="facebook_url">
                <img src="{{ asset('assets/images/social-facebook.png') }}">
            </a>
        @endif

        @if (! empty($website->instagram_url))
            <a href="{{ $website->instagram_url }}" target="_blank" class="social-icon" data-field-name="instagram_url">
                <img src="{{ asset('assets/images/social-instagram.png') }}">
            </a>
        @endif

        @if (! empty($website->pinterest_url))
            <a href="{{ $website->pinterest_url }}" target="_blank" class="social-icon" data-field-name="pinterest_url">
                <img src="{{ asset('assets/images/social-pinterest.png') }}">
            </a>
        @endif
    </div>
    <div class="social-grid-cell social-grid-service-cell">
        <a class="social-media-service-value" data-value="{{ $website->social_media_service }}"></a>
    </div>
    <div class="social-grid-cell social-grid-assignee-cell">
        <a class="social-media-assignee-value" data-value="{{ $website->social_media_assignee }}"></a>
    </div>
    <div class="social-grid-cell social-grid-reviewer-cell">
        <a class="social-media-reviewer-value" data-value="{{ $website->social_media_reviewer }}"></a>
    </div>
    <div class="social-grid-cell social-grid-progress-cell">
        <strong>
            <span class="social-media-checklist-count-value">{{ $website->socialMediaCheckLists->count() }}</span>/{{ $website->getActiveSocialMediaCheckListCount() }}
        </strong>
    </div>
</div>
