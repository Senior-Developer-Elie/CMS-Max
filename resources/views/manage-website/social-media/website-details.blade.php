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