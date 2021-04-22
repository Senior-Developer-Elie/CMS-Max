<div class="social-grid-row website-row" data-website-id = {{ $website->id }}>
    <div class="social-grid-cell social-grid-name-cell">
        {{ $website->name }}

        <span class="website-icons-wrapper">
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
                <a class="website-icon">
                    <img src="{{ asset('assets/images/social-calendar-disabled-icon.png') }}" />
                </a>
            @endif
        </span>
    </div>
    <div class="social-grid-cell social-grid-progress-cell">
        <strong>
            <span class="social-media-checklist-count-value">{{ $website->socialMediaCheckLists->count() }}</span>/{{ count($socialMediaCheckLists) }}
        </strong>
    </div>
</div>
