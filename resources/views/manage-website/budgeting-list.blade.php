@extends('layouts.theme')

@section('content-header')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <i class="fa fa-calendar-times-o"></i>

                    <h3 class="card-title">Websites For Budgeting : {{ count($websites) }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="show-unsynced" {{ $filterType == 'unsynced' ? 'checked' : '' }}>
                        <label class="form-check-label" for="show-unsynced">Show UnSynced Websites From API</label>
                    </div>
                    <table id = "website-list-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="200px">Website</th>
                                @foreach (\App\AngelInvoice::budgetingApiProductKeys() as $apiProductKey)
                                    <th class="text-center" style="white-space: nowrap;">{{ $apiProductKey }}</th>    
                                @endforeach
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $websites as $website )
                                <tr data-website-id="{{ $website->id }}">
                                    <?php
                                        $websiteService = $website->getProductValues(\App\AngelInvoice::budgetingCrmProductKeys());
                                    ?>
                                    <td class="website-url-wrapper">
                                        <a href="{{ route('websites.edit', $website) }}" data-toggle="tooltip" data-placement="top" title="Edit Website" data-html="true">
                                            {{ $website->website }}
                                        </a>
                                        <a class="website-info-icon" href = "//{{ $website->website }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Go to Website">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </td>
                                    @foreach (\App\AngelInvoice::budgetingCrmProductKeys() as $crmProductKey)
                                        <td class="text-center">
                                            <span data-value="{{ $websiteService[$crmProductKey] }}">
                                                {{ getPrettyServiceString($websiteService[$crmProductKey], true) }}
                                            </span>
                                        </td>
                                    @endforeach
                                    <td class="text-center">
                                        <span data-value="{{ $websiteService['total'] }}">
                                            {{ getPrettyServiceString($websiteService['total']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                @foreach (\App\AngelInvoice::budgetingCrmProductKeys() as $crmProductKey)
                                    <th class="text-center"></th>
                                @endforeach
                                <th class="text-center"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ mix("css/datatable.css") }}" rel="stylesheet">
@endsection
@section('javascript')
    <script src="{{ mix('js/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/website/budgeting.js?v=31') }}"></script>
@endsection
