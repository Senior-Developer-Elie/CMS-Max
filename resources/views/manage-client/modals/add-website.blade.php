<!--Add Website Modal-->
<div class="modal fade" id="add-website-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Website</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="website-form">
                    <div class="form-group">
                        <label for="website-name">Website Name</label>
                        <div class="">
                            <input type="text" class="form-control" id="website-name" name="name" placeholder="Enter target area" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="website-url">Website Url</label>
                        <div class="">
                            <input type="text" class="form-control" id="website-url" name="website" placeholder="Enter target area" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="target-area">Target Area</label>
                        <div class="">
                            <input type="text" class="form-control" id="target-area" name="target_area" placeholder="Enter target area" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Industry</label>
                        <div class="">
                            <select class="form-control blog-industry-list" name="blog_industry_id" style="width: 100%;">
                                <option value="">- Please Select a Industry -</option>
                                @foreach ($blogIndustries as $blogIndustry)
                                    <option value="{{ $blogIndustry->id }}">{{ $blogIndustry->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Website Type</label>
                        <div class="">
                            <select class="form-control website-type-list" name="type" style="width: 100%;">
                                @foreach ($allWebsiteTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Completed Date:</label>
                        <div class="">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </div>
                                <input type="text" class="form-control pull-right completed-date" name="completed_at">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="google-drive">Google Drive</label>
                        <div class="">
                            <input type="text" class="form-control" id="google-drive" name="drive" placeholder="Enter target area" required>
                        </div>
                    </div>
                    <div class="form-group" style="display:none;">
                        <label for="blog-industry">Shipping Method</label>
                        <div class="">
                            <select class="form-control website-shipping-method-list" name="shipping_method" style="width: 100%;">
                                @foreach ($allShippingMethodTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Affiliate</label>
                        <div class="">
                            <select class="form-control website-affiliate-list" name="affiliate" style="width: 100%;">
                                @foreach ($allAffiliateTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">DNS</label>
                        <div class="">
                            <select class="form-control website-dns-list" name="dns" style="width: 100%;">
                                @foreach ($allDNSTypes as $dnsType)
                                    <option value="{{ $dnsType['value'] }}">{{ $dnsType['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Email</label>
                        <div class="">
                            <select class="form-control website-email-list" name="email" style="width: 100%;">
                                <option value="">--Please Select Email--</option>
                                @foreach ($allEmailTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Payment Gateway</label>
                        <div class="">
                            <select class="form-control website-payment-gateway-list" name="payment_gateway" multiple="multiple" data-placeholder="Select Payment Gateways" style="width: 100%;">
                                @foreach ($allPaymentGateways as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!--TSYS Fields-->
                    <div class="tsys-fields-wrapper">
                        <div class="form-group">
                            <label for="mid">MID</label>
                            <div class="">
                                <input type="text" class="form-control" id="mid" name="mid" placeholder="MID">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="control-scan-user">Control Scan User</label>
                            <div class="">
                                <input type="text" class="form-control" id="control-scan-user" name="control_scan_user" placeholder="Control Scan User">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="control-scan-pass">Control Scan Pass</label>
                            <div class="">
                                <input type="text" class="form-control" id="control-scan-pass" name="control_scan_pass" placeholder="Control Scan Pass">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Control Scan Renewal Date:</label>
                            <div class="">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="control-scan-renewal-date" name="control_scan_renewal_date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="blog-industry">Sitemap</label>
                        <div class="">
                            <select class="form-control website-sitemap-list" name="sitemap" style="width: 100%;">
                                @foreach ($allSitemapTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Left Review</label>
                        <div class="">
                            <select class="form-control website-left-review-list" name="left_review" style="width: 100%;">
                                <option value="">Please Select</option>
                                @foreach ($allLeftReviewTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog-industry">Portfolio</label>
                        <div class="">
                            <select class="form-control website-portfolio-list" name="on_portfolio" style="width: 100%;">
                                <option value="">Please Select</option>
                                @foreach ($allPortfolioTypes as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="data-studio-link">Data Studio Link</label>
                        <div class="">
                            <input type="text" class="form-control" id="data-studio-link" name="data_studio_link" placeholder="Data Studio Link">
                        </div>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id = "chargebee-checkbox" name="chargebee">
                            <strong class="ml-1">Chargebee</strong>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id = "sync-from-client-checkbox" name="sync_from_client">
                            <strong class="ml-1">Sync From Client</strong>
                        </label>
                    </div>
                    <div class="form-group" style="display:none;">
                        <label>Service</label>
                        <div class="d-flex">
                            <select class="form-control service-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control service-frequency-list" name = "service_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-service-value" class = "form-control manual-value-input" type="text" name="service" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Support Maintenance</label>
                        <div class="d-flex">
                            <select class="form-control support_maintenance-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control support_maintenance-frequency-list" name = "support_maintenance_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-support_maintenance-value" class = "form-control manual-value-input" type="text" name="support_maintenance" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>SEO</label>
                        <div class="d-flex">
                            <select class="form-control internet_marketing-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control internet_marketing-frequency-list" name = "internet_marketing_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-internet_marketing-value" class = "form-control manual-value-input" type="text" name="internet_marketing" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Listings Management</label>
                        <div class="d-flex">
                            <select class="form-control yext-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-needed">Not Needed</option>
                                <option value="need-to-sell">Need to Sell</option>
                                <option value="not-interested">Not Interested</option>
                            </select>
                            <select class="form-control yext-frequency-list" name = "yext_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-yext-value" class = "form-control manual-value-input" type="text" name="yext" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Google Workspace</label>
                        <div class="d-flex">
                            <select class="form-control gsuite-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control gsuite-frequency-list" name = "g_suite_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-gsuite-value" class = "form-control manual-value-input" type="text" name="g_suite" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label>SSL</label>
                        <div class="d-flex">
                            <select class="form-control ssl-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control ssl-frequency-list" name = "ssl_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-ssl-value" class = "form-control manual-value-input" type="text" name="ssl" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label>Hosting</label>
                        <div class="d-flex">
                            <select class="form-control hosting-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control hosting-frequency-list" name = "hosting_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-hosting-value" class = "form-control manual-value-input" type="text" name="hosting" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Google Ads</label>
                        <div class="d-flex">
                            <select class="form-control googleAds-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control googleAds-frequency-list" name = "googleAds_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-googleAds-value" class = "form-control manual-value-input" type="text" name="googleAds" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Google Management Fee</label>
                        <div class="d-flex">
                            <select class="form-control googleManagementFee-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control googleManagementFee-frequency-list" name = "googleManagementFee_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-googleManagementFee-value" class = "form-control manual-value-input" type="text" name="googleManagementFee" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>CMS Max</label>
                        <div class="d-flex">
                            <select class="form-control cmsmax_software-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control cmsmax_software-frequency-list" name = "cmsmax_software_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-cmsmax_software-value" class = "form-control manual-value-input" type="text" name="cmsmax_software" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>CMS Max Plus</label>
                        <div class="d-flex">
                            <select class="form-control cms_max_plus-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control cms_max_plus-frequency-list" name = "cms_max_plus_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-cms_max_plus-value" class = "form-control manual-value-input" type="text" name="cms_max_plus" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>CMS Max eCommerce</label>
                        <div class="d-flex">
                            <select class="form-control cmsmax_ecommerce_software-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control cmsmax_ecommerce_software-frequency-list" name = "cmsmax_ecommerce_software_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-cmsmax_ecommerce_software-value" class = "form-control manual-value-input" type="text" name="cmsmax_ecommerce_software" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>CMS Max eCommerce Plus</label>
                        <div class="d-flex">
                            <select class="form-control cms_max_ecommerce_plus-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control cms_max_ecommerce_plus-frequency-list" name = "cms_max_ecommerce_plus_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-cms_max_ecommerce_plus-value" class = "form-control manual-value-input" type="text" name="cms_max_ecommerce_plus" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Social Media Management</label>
                        <div class="d-flex">
                            <select class="form-control social_media_management-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control social_media_management-frequency-list" name = "social_media_management_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-social_media_management-value" class = "form-control manual-value-input" type="text" name="social_media_management" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Domain</label>
                        <div class="d-flex">
                            <select class="form-control domain-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control domain-frequency-list" name = "domain_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-domain-value" class = "form-control manual-value-input" type="text" name="domain" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>DontGo</label>
                        <div class="d-flex">
                            <select class="form-control dont_go-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control dont_go-frequency-list" name = "dont_go_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-dont_go-value" class = "form-control manual-value-input" type="text" name="dont_go" placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>OrderSnapp</label>
                        <div class="d-flex">
                            <select class="form-control order_snapp-availability-list value-availability-list" style="width: 39%;">
                                <option value="available">Available</option>
                                <option value="not-available">Not Available</option>
                            </select>
                            <select class="form-control order_snapp-frequency-list" name = "order_snapp_frequency" style="width: 40%; margin-left: 1%;">
                                <option value="1">Monthly</option>
                                <option value="12">Yearly</option>
                            </select>
                            <input id = "manual-order_snapp-value" class = "form-control manual-value-input" type="text" name="order_snapp" placeholder="0">
                        </div>
                    </div>

                    <div class="checkbox" style="margin-left: 10px; margin-bottom: 20px;">
                        <label>
                            <input type="checkbox" id = "blog-client-checkbox" name="is_blog_client"><strong>Enable Blog</strong>
                        </label>
                    </div>

                    <div id="client-detail-info-wrapper" >
                        <div class="form-group">
                            <label for="admin-list">Writer</label>
                            <div class="">
                                <select class="form-control admins-list" name="assignee_id" style="width: 100%;">
                                    <option value="">- Please Select an Admin -</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Blog Frequency</label>
                            <div class="">
                                <select class="form-control frequency-list" name="frequency" style="width: 100%">
                                    <option value="" >Please Select Frequency</option>
                                    <option value="monthly" >Monthly</option>
                                    <option value="bi-monthly">Bi-Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="6 months">6 Months</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Start Date:</label>
                            <div class="">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="start-date" name="start_date" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                @can('delete ability')
                    <button type="button" class="btn btn-danger pull-left remove-btn">Remove Website</button>
                    <button type="button" class="btn btn-warning pull-left archive-btn" style="display:none;">Archive Website</button>
                    <button type="button" class="btn btn-info pull-left unarchive-btn" style="display:none;" >Re-enable Website</button>
                @endcan
                <button type="button" class="btn btn-primary pull-right confirm-btn">OK</button>
            </div>
        </div>
    </div>
</div>
