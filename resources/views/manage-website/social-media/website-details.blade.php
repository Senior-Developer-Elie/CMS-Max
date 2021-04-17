<div id="website-details-wrapper" style="display:none">
    <div class="website-details-header">
        <h4 class="website-name-value-wrapper">
            <span class="website-name-value"></span>
            <a href="" target="_blank" class="website-edit-link">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </h4>
        <div class="website-tool-bar-buttons-wrapper">
            <a data-toggle="tooltip" data-placement="left" title="" data-html="true" class="header-tool-button hide-button" data-original-title="Close details">
                <svg focusable="false" viewBox="0 0 32 32" class="icon"><path d="M2,14.5h18.4l-7.4-7.4c-0.6-0.6-0.6-1.5,0-2.1c0.6-0.6,1.5-0.6,2.1,0l10,10c0.6,0.6,0.6,1.5,0,2.1l-10,10c-0.3,0.3-0.7,0.4-1.1,0.4c-0.4,0-0.8-0.1-1.1-0.4c-0.6-0.6-0.6-1.5,0-2.1l7.4-7.4H2c-0.8,0-1.5-0.7-1.5-1.5C0.5,15.3,1.2,14.5,2,14.5z M28,3.5C28,2.7,28.7,2,29.5,2S31,2.7,31,3.5v25c0,0.8-0.7,1.5-1.5,1.5S28,29.3,28,28.5V3.5z"></path></svg>
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
                    <label class="attribute-name">Check List</label>
                    <div class="form-group check-list-checkboxes">
                        @foreach (\App\WebsiteSocialMediaCheckList::socialMediaCheckLists() as $key => $name)
                            <div class="checkbox">
                                <label>
                                    <input class = "check-list-option" type="checkbox" data-check-list-key="{{ $key }}">
                                    {{ $name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>