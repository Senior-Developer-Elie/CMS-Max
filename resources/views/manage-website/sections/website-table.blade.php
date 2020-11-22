@if( $archived )
    <table id = "archived-website-list-table" class="table table-bordered table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Website</th>
                <th>Website Name</th>
                <th>Website Type</th>
                <th>Target Area</th>
                <th>Industry</th>
                <th>Archived At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($websites as $website)
                <tr data-website-id={{ $website->id }}>
                    <td class="website-url-wrapper">
                        <a href="#" class="website-url" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                            {{ $website->website }}
                        </a>
                        <div class="website-info-icons">
                            @if( !empty($website->drive) )
                                <a class="website-google-drive-link-icon" href = "{{ $website->drive }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Google Drive">
                                    <img src="{{ asset('assets/images/google-drive-icon.png') }}" />
                                </a>
                            @endif
                            @if( !empty($website->data_studio_link) )
                                <a class="website-data-studio-link-icon" href = "{{ $website->data_studio_link }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Data Studio Link">
                                    <img src="{{ asset('assets/images/data-studio-icon.png') }}" />
                                </a>
                            @endif
                            <a class="website-info-icon" href = "//{{ $website->website }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Go to Website">
                                <img src="{{ asset('assets/images/info-icon.png') }}" />
                            </a>
                            @if ($website->chargebee)
                                <img src="{{ asset('assets/images/chargebee_favicon.png') }}" />
                            @endif
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('client-history?clientId=' . $website->client()->id) }}">
                            {{ $website->name }}
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-type" data-value="{{ $website->type }}" data-shipping-method="{{ $website->shipping_method }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-target-area" data-value="{{ $website->target_area }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-industry" data-value="{{ $website->blog_industry_id }}">
                        </a>
                    </td>
                    <td>
                        {{ (new \Carbon\Carbon($website->archived_at))->format('m/d/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table id = "website-list-table" class="table table-bordered table-striped" style="width:100%">
        <thead>
            <tr>
                <th width="140px">Website</th>
                <th>Website Name</th>
                <th>Listings Management</th>
                <th width="150px">Email</th>
                <th width="100px">DNS</th>
                <th width="80px">Payment Gateway</th>
                <th>Left Review</th>
                <th>On Portfolio</th>
                <th width="100px">Website Type</th>
                <th>Affiliate</th>
                <th>Target Area</th>
                <th>Industry</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($websites as $website)
                <tr data-website-id={{ $website->id }}>
                    <td class="website-url-wrapper">
                        <a href="#" class="website-url" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                            {{ $website->website }}
                        </a>
                        <div class="website-info-icons">
                            @if( !empty($website->drive) )
                                <a class="website-google-drive-link-icon" href = "{{ $website->drive }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Google Drive">
                                    <img src="{{ asset('assets/images/google-drive-icon.png') }}" />
                                </a>
                            @endif
                            @if( !empty($website->data_studio_link) )
                                <a class="website-data-studio-link-icon" href = "{{ $website->data_studio_link }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Data Studio Link">
                                    <img src="{{ asset('assets/images/data-studio-icon.png') }}" />
                                </a>
                            @endif
                            <a class="website-info-icon" href = "//{{ $website->website }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Go to Website">
                                <img src="{{ asset('assets/images/info-icon.png') }}" />
                            </a>
                            @if ($website->chargebee)
                                <a class="website-chargebee-icon">
                                    <img src="{{ asset('assets/images/chargebee_favicon.png') }}" />
                                </a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('client-history?clientId=' . $website->client()->id) }}">
                            {{ $website->name }}
                            @if( Auth::user()->hasRole('super admin') && $website->sync_from_client )
                                <i class="fas fa-sync ml-1" style="font-size: 12px"></i>
                            @endif
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="#" class="website-yext" data-value="{{ $website->getProductValue(\App\AngelInvoice::CRM_KEY_LISTINGS_MANAGEMENT) }}">
                            {{ getPrettyServiceString($website->getProductValue(\App\AngelInvoice::CRM_KEY_LISTINGS_MANAGEMENT), true) }}
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-email" data-value="{{ $website->email }}" 
                            data-price="{{ getPrettyServiceString(intval($website->getProductValue(\App\AngelInvoice::CRM_KEY_GOOGLE_WORKSPACE))) }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-dns" data-value={{ $website->dns }}>
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-payment-gateway" data-value="{{ is_array($website->payment_gateway) ? implode(',', $website->payment_gateway) : '' }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-left-review" data-value="{{ $website->left_review }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-on-portfolio" data-value="{{ $website->on_portfolio }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-type" data-value="{{ $website->type }}" data-shipping-method="{{ $website->shipping_method }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-affiliate" data-value="{{ $website->affiliate }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-target-area" data-value="{{ $website->target_area }}">
                        </a>
                    </td>
                    <td>
                        <a href="#" class="website-industry" data-value="{{ $website->blog_industry_id }}">
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
