<div id="task-details-wrapper" style="display:none">
    <div class="TaskNameWrapper">
        <h4 class="task-name-value"></h4>
        <!--
        <a class="header-tool-button add-comment-button" data-toggle="tooltip" data-placement="top" title="Add Comment<br>Ctrl + Enter" data-html="true">
            <i class="fa fa-commenting-o"></i>
        </a>
        -->
        <a class="header-tool-button add-file-button" data-toggle="tooltip" data-placement="top" title="Add attachments" data-html="true">
            <svg class="Icon" focusable="false" viewBox="0 0 32 32"><path d="M19,32c-3.9,0-7-3.1-7-7V10c0-2.2,1.8-4,4-4s4,1.8,4,4v9c0,0.6-0.4,1-1,1s-1-0.4-1-1v-9c0-1.1-0.9-2-2-2s-2,0.9-2,2v15c0,2.8,2.2,5,5,5s5-2.2,5-5V10c0-4.4-3.6-8-8-8s-8,3.6-8,8v5c0,0.6-0.4,1-1,1s-1-0.4-1-1v-5C6,4.5,10.5,0,16,0s10,4.5,10,10v15C26,28.9,22.9,32,19,32z"></path></svg>
        </a>
        <a class="header-tool-button copy-link-button" data-toggle="tooltip" data-placement="top" title="Copy task link" data-html="true">
            <svg class="Icon" focusable="false" viewBox="0 0 32 32"><path d="M9,32c-2.3,0-4.6-0.9-6.4-2.6c-3.5-3.5-3.5-9.2,0-12.7l4-4c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4l-4,4c-2.7,2.7-2.7,7.2,0,9.9s7.2,2.7,9.9,0l4-4c2.7-2.7,2.7-7.2,0-9.9c-0.8-0.8-1.8-1.4-2.9-1.7c-0.5-0.2-0.8-0.7-0.7-1.3c0.2-0.5,0.7-0.8,1.3-0.7c1.4,0.4,2.7,1.2,3.7,2.2c3.5,3.5,3.5,9.2,0,12.7l-4,4C13.6,31.1,11.3,32,9,32z M16.6,21.6c-0.1,0-0.2,0-0.3,0c-1.4-0.4-2.7-1.2-3.7-2.2c-1.7-1.7-2.6-4-2.6-6.4s0.9-4.7,2.6-6.4l4-4c3.5-3.5,9.2-3.5,12.7,0s3.5,9.2,0,12.7l-4,4c-0.4,0.4-1,0.4-1.4,0s-0.4-1,0-1.4l4-4c2.7-2.7,2.7-7.2,0-9.9S20.7,1.3,18,4l-4,4c-1.3,1.4-2,3.1-2,5s0.7,3.6,2.1,5c0.8,0.8,1.8,1.4,2.9,1.7c0.5,0.2,0.8,0.7,0.7,1.3C17.5,21.4,17.1,21.6,16.6,21.6z"></path></svg>
        </a>
        @can('delete ability')
            <a class="header-tool-button delete-button" data-toggle="tooltip" data-placement="top" title="Delete Task" data-html="true">
                <i class="far fa-trash-alt"></i>
            </a>
        @endcan
        @if( Auth::user()->hasRole('super admin') )
            <a class="header-tool-button complete-button" data-toggle="tooltip" data-placement="top" title="Complete Website" data-html="true">
                <i class="fas fa-check"></i>
            </a>
        @endif
        <a class="header-tool-button hide-button" data-toggle="tooltip" data-placement="top" title="Close details" data-html="true">
            <svg class="Icon" focusable="false" viewBox="0 0 32 32"><path d="M2,14.5h18.4l-7.4-7.4c-0.6-0.6-0.6-1.5,0-2.1c0.6-0.6,1.5-0.6,2.1,0l10,10c0.6,0.6,0.6,1.5,0,2.1l-10,10c-0.3,0.3-0.7,0.4-1.1,0.4c-0.4,0-0.8-0.1-1.1-0.4c-0.6-0.6-0.6-1.5,0-2.1l7.4-7.4H2c-0.8,0-1.5-0.7-1.5-1.5C0.5,15.3,1.2,14.5,2,14.5z M28,3.5C28,2.7,28.7,2,29.5,2S31,2.7,31,3.5v25c0,0.8-0.7,1.5-1.5,1.5S28,29.3,28,28.5V3.5z"></path></svg>
        </a>
    </div>
    <div class="scroll-bar-wrap">
        <div class="scroll-box">
            <div class="Attributes-wrapper">
                {{--
                <div class="attribute-row">
                    <label class="attribute-name">Due Date</label>
                    <a href="#" class="attribute-value due-datedue-value"></a>
                </div>
                --}}
                <div class="attribute-row">
                    <label class="attribute-name">Client</label>
                    <a href="#" class="attribute-value client-name-value"></a>
                    <a class="show-client show-link-button" target="_blank"><i class="fas fa-link"></i></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Dev URL</label>
                    <a href="#" class="attribute-value dev-url-value"></a>
                    <a class="show-dev-url show-link-button" target="_blank"><i class="fas fa-link"></i></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Live URL</label>
                    <label class="attribute-value live-url-value"></label>
                    <a class="show-live-url show-link-button" target="_blank"><i class="fas fa-link"></i></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Drive</label>
                    <a href="#" class="attribute-value client-drive-value"></a>
                    <a class="show-drive show-link-button" target="_blank"><i class="fas fa-link"></i></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Mail Host</label>
                    <label class="attribute-value mail-host-value"></label>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Sitemap</label>
                    <label class="attribute-value site-map-value"></label>
                    <a class="show-site-map show-link-button" target="_blank"><i class="fas fa-link"></i></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Home Page Copy</label>
                    <label class="attribute-value home-page-copy-value"></label>
                    <a class="show-home-page-copy show-link-button" target="_blank"><i class="fas fa-link"></i></a>
                </div>
                <div class="attribute-row">
                    <label class="attribute-name">Pre-Live</label>
                    <div class="form-group attribute-wrapper pre-live-options">
                        @foreach ( $allPreLiveOptions as $key => $option )
                            <div class="checkbox">
                                <label>
                                    <input class = "pre-post-option" type="checkbox" data-option-value="{{ $key }}">
                                    {{ $option }}
                                </label>
                                <span class="completed_by text-success" style="display:none;">({{ in_array($key, ['favicon','social-media-image']) ? 'Added by' : 'Checked By' }} <strong class="name">Victor Kovtun</strong> on <strong class="date"></strong>)</span>
                                <span class="uploaded_by text-info" style="display:none;">(Uploaded By <strong class="name"></strong> on <strong class="date"></strong>)</span>
                                <a href="#" class="upload-btn" data-toggle="tooltip" data-placement="top" title="Upload Image" style="display:none"><i class="fas fa-upload"></i></a>
                                <a href="#" class="download-btn" data-toggle="tooltip" data-placement="top" title="Download Image" style="display:none;"><i class="fas fa-download"></i></a>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="SingleTaskPane-Separator"></div>

            <h4 class="description-intro-title">Description</h4>

            <div class="SingleTaskPane description-wrapper">
                <div class="SingleTaskPane-icon descriptionIcon">
                    <svg class="Icon" focusable="false" viewBox="0 0 32 32">
                        <path
                            d="M31,8H1C0.4,8,0,7.6,0,7s0.4-1,1-1h30c0.6,0,1,0.4,1,1S31.6,8,31,8z M23,14H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h22c0.6,0,1,0.4,1,1S23.6,14,23,14z M27,20H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h26c0.6,0,1,0.4,1,1S27.6,20,27,20z M19,26H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h18c0.6,0,1,0.4,1,1S19.6,26,19,26z">
                        </path>
                    </svg>
                </div>
                <div class="TaskAttributeContent description-content">
                    <div class="task-description-value" data-toggle="tooltip" data-placement="top" title="Click to Edit" data-html="true">
                    </div>
                    <textarea class="task-description-edit-textarea" style="display:none;"></textarea>
                    <button id="task-description-confirm-btn" type="button" style="display:none"></button>
                </div>
            </div>

            <div class="SingleTaskPane">
                <div class="SingleTaskPane-icon">
                    <svg class="Icon" focusable="false" viewBox="0 0 32 32">
                        <path d="M25.811,4.064c-3.905-3.904-10.235-3.904-14.14,0l-4.24,4.24l1.41,1.41l4.25-4.24c3.043-3.203,8.107-3.333,11.31-0.29s3.333,8.107,0.29,11.31c-0.094,0.099-0.191,0.196-0.29,0.29l-10.61,10.59c-1.986,1.918-5.152,1.863-7.07-0.123c-1.871-1.938-1.871-5.01,0-6.947l10.61-10.59c0.781-0.781,2.049-0.781,2.83,0s0.781,2.049,0,2.83l-7.07,7.07l1.41,1.42l7.07-7.07c1.563-1.563,1.563-4.097,0-5.66s-4.097-1.563-5.66,0l-10.6,10.61c-2.734,2.734-2.734,7.166,0,9.9s7.166,2.734,9.9,0l0,0l10.6-10.61C29.715,14.299,29.715,7.969,25.811,4.064z"></path>
                    </svg>
                </div>
                <div class="TaskAttributeContent">
                    <ul class="task-attachment-list">
                    </ul>
                </div>
            </div>

            <div class="SingleTaskPane-Separator"></div>
            <div class="comments-box-wrapper">
            </div>
        </div>
        <div class="DropTargetAttachment-target" onmousedown="return false">
            <div class="DropTargetAttachment-border">
                <img src="https://d3ki9tyy5l5ruj.cloudfront.net/obj/a1c9feeb705b96fec03a072d3050f7588b33d624/drop_files.svg" class="DropTargetAttachment-image"><span class="DropTargetAttachment-imageText">Drop to attach files</span>
            </div>
        </div>
        <div class="cover-bar"></div>
    </div>
    <div class="sing-task-pane-footer">
        <div id="comment-composer-box"></div>
    </div>
</div>

<!--Hidden Input Fields For Upload -->
<input type="file" id="task-file-input" name="taskFile[]" style="display:none" multiple>

<!--Hidden Input Field For Image Upload For Pre Live Options-->
<input type="file" id="task-pre-live-image-file" style="display:none" accept="image/*">

<!--Hidden File Download Form--->
<form id = "download-pre-image-form" method="GET" action="{{ url('/task-download-pre-image') }}" style="display:none" target="_blank">
    <input type="hidden" class='task-id' name="taskId">
    <input type="hidden" class='option' name="option">
</form>

<!--Hidden File Download Form--->
<form id = "file-download-form" method="GET" action="{{ url('/task-download-file') }}" style="display:none" target="_blank">
    <input type="hidden" name="taskFileId">
</form>

@include('website-progress.sections.attachment-item')
@include('website-progress.sections.comment-block')
