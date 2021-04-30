<div id="website-details-wrapper" style="display:none">
    <div class="website-details-header">
        <h4 class="website-name-value-wrapper">
            <span class="website-name-value"></span>
            <a href="" target="_blank" class="website-edit-link">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </h4>
        <div class="website-tool-bar-buttons-wrapper">
            <a class="header-tool-button mark-inactive-button" data-toggle="tooltip" data-placement="left" title="Mark as Inactive" data-html="true">
                <i class="far fa-trash-alt"></i>
            </a>
            <a data-toggle="tooltip" data-placement="left" title="" data-html="true" class="header-tool-button hide-button" data-original-title="Close details">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="scroll-bar-wrap">
        <div class="scroll-box">
            <div class="attribute-wrapper">
                <div class="attribute-row">
                    <label class="attribute-name">Stage</label>
                    <a href="#" class="attribute-value stage-value"></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Client</label>
                    <span class="attribute-value client-name-value"></span>
                    <a href="" target="_blank" class="link-button client-edit-link">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Website</label>
                    <span class="attribute-value website-url-value"></span>
                    <a href="" target="_blank" class="link-button website-url-link">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Budget</label>
                    <span class="attribute-value budget-value"></span>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Plan</label>
                    <a href="#" class="attribute-value social-plan-value"></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Ad Spend</label>
                    <a href="#" class="attribute-value ad-spend-value"></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Management Fee</label>
                    <a href="#" class="attribute-value management-fee-value"></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Notes</label>
                    <a href="#" class="attribute-value notes-value"></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name"></label>
                    <div class="social-links attribute-value">
                        <a href="https://www.linkedin.com/company/evolution-marketing-inc" target="_blank" class="social-icon" data-field-name="linkedin_url">
                            <img src="https://media.cmsmax.com/fjliwncknaoc0txjp1m4f/icon-linkedin.svg">
                        </a>
                        <a href="https://www.linkedin.com/company/evolution-marketing-inc" target="_blank" class="social-icon" data-field-name="youtube_url">
                        <img src="https://media.cmsmax.com/fjliwncknaoc0txjp1m4f/icon-youtube.svg">
                        </a>
                        <a href="https://www.linkedin.com/company/evolution-marketing-inc" target="_blank" class="social-icon" data-field-name="twitter_url">
                            <img src="https://media.cmsmax.com/fjliwncknaoc0txjp1m4f/icon-twitter.svg">
                        </a>
                        <a href="https://www.linkedin.com/company/evolution-marketing-inc" target="_blank" class="social-icon" data-field-name="facebook_url">
                            <img src="https://media.cmsmax.com/fjliwncknaoc0txjp1m4f/icon-facebook.svg">
                        </a>
                        <a href="https://www.linkedin.com/company/evolution-marketing-inc" target="_blank" class="social-icon" data-field-name="instagram_url">
                            <img src="https://media.cmsmax.com/fjliwncknaoc0txjp1m4f/icon-instagram.svg">
                        </a>
                        <a href="https://www.linkedin.com/company/evolution-marketing-inc" target="_blank" class="social-icon" data-field-name="pinterest_url">
                            <img src="https://media.cmsmax.com/fjliwncknaoc0txjp1m4f/icon-pinterest.svg">
                        </a>
                    </div>
                </div>

                <div class="check-list-checkboxes">
                    @foreach (\App\SocialMediaCheckList::checkListTypes() as $checkListKey => $checkListName)
                        @php
                            $socialMediaCheckLists = \App\SocialMediaCheckList::byTarget($checkListKey)->orderBy('order')->get();
                        @endphp

                        <div class="attribute-row" data-social-media-checklist-target="{{ $checkListKey }}">
                            <label class="attribute-name">{{ $checkListName }}</label>
                            <div class="form-group">
                                @foreach ($socialMediaCheckLists as $socialMediaCheckList)
                                    <div class="checkbox mb-1">
                                        <label class="m-0">
                                            <input class = "check-list-option" type="checkbox" data-social-media-check-list-id="{{ $socialMediaCheckList->id }}">
                                            <span>
                                                {{ $socialMediaCheckList->text }}
                                                <span class="completed_by text-success">
                                                    (Checked By <strong class="name">Sam Pizzo</strong> on <strong class="date">04/30</strong>)
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>