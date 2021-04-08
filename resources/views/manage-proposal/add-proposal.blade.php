@extends('layouts.theme')

@section('content-header')
@endsection

@section('content')
    <div class = "container mt-2">

        <div class = "logo-wrapper text-center">
            <img id = "cms-logo" src = "">
        </div>

        <h4 class = "text-center m-2">Building & Branding Custom Websites that Get Results!</h4>

        <form id = "proposal-form" method="{{ isset($editMode) ? 'POST' : 'GET' }}" action="{{ isset($editMode) ? '/edit-proposal' : '/process' }}">
            @csrf
            <div class="form-group template-type-form-group">
                <select class="form-group template-type-select" name="template_type">
                    @foreach (\App\Services\ProposalTemplateService::TEMPLATE_DATA as $templateKey => $templateData)
                        <option value="{{ $templateKey }}" {{ (isset($editMode) && $proposal->template_type == $templateKey) ? 'selected' : '' }}>{{ $templateData['prepared_by'] }}</option>
                    @endforeach
                </select>
            </div>
            <p class = "text-center mt-3 custom-form-group">
                <input type="text" class="form-control" name="clientName" placeholder="Client Name" value = "{{ isset($editMode) ? $request['clientName'] : '' }}"> - This will automatically form the sentence<br>
                <strong>Proposal Presented by CMS Max, Inc. to <span class = "client-name">Client Name</span></strong>
            </p>

            <p class = "text-center custom-form-group">
                <input type="text" class="form-control" name="websiteUrl" placeholder="Website URL" value = "{{ isset($editMode) ? $request['websiteUrl'] : '' }}"> - This will automatically form the sentence<br>
                <strong>Summary of Service Fees for Build and Maintenance of <span class = "website-url">Website Url</span></strong>
            </p>

            <div class="row mt-3 service-content-wrapper">
                <div class = "col-md-6">
                    <div class = "card">
                        <div class = "row service-container">
                            <div class = "col-md-7">
                                <h5>Select Services</h5>
                            </div>
                            <div class="col-md-5">
                                <h5>Price</h5>
                            </div>
                            @foreach($services as $service)
                                @if( $service['type'] == 'one-time' || $service['type'] == 'recurring' )
                                    <div class = "col-md-7">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="{{ $service['name'] }}" name = "{{ $service['name'] }}">
                                            <label class="form-check-label" for="{{ $service['name'] }}">
                                                {!! $service['label'] !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="{{ $service['name'] }}-price" value = "{{ ( isset($editMode) && isset($request[$service['name'] . '-price']) ) ? $request[$service['name'] . '-price'] : $service['price'] }}" disabled>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class = "row mt-3">
                        <div class = "col-md-12">
                            <div class="form-group">
                                <label for="bottom-description">Content in the bottom</label>
                                <textarea class="form-control" name = "bottomDescription" id="bottom-description" rows="6"></textarea>
                            </div>
                        </div>
                        @if( isset($editMode) )
                            <input type = "hidden" name="proposalId" value="{{ $proposalId }}" />
                        @else
                            <div class = "col-md-12">
                                <div class="ml-4 form-check">
                                    <input class="form-check-input" id = "add-signature" type="checkbox" name="addSignature">
                                    <label class="form-check-label" for="add-signature">
                                        Add Signature
                                    </label>
                                </div>
                            </div>
                            <div class = "col-md-5">
                                <div class="ml-4 form-check">
                                    <input class="form-check-input" id = "email-contact" type="checkbox" name="emailContact">
                                    <label class="form-check-label" for="email-contact">
                                        Email Contact
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-7 client-email-wrapper">
                                <input id = "client-email" type="text" class="form-control" name="clientEmail" placeholder = "Email address" style="display:none">
                            </div>
                            <div class="col-md-12">
                                <p class = "email-contact-description">*if this is checked it will display a text box to type someone&apos;s email in to send this to so that they can sign online</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <input id = "request-type-field" type = "hidden" name="requestType" value="preview" />
        </form>

        <div class = "row preview-button-wrapper">
            @if( isset($editMode) )
                <button id = "update-proposal-button" type="button" class="btn btn-info mr-2">Update Proposal</button>
            @else
                <button id = "preview-button" type="button" class="btn btn-info mr-2">Preview Proposal</button>
            @endif
        </div>
    </div>

    <!--Preview Modal-->
    <div class="modal fade" id="preview-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe id = "preview-frame" name = "preview-frame" scrolling="no">
                    </iframe>
                    <button id = "confirm-proposal-button" class="btn btn-primary btn-block">Confirm Proposal</button>
                    <button id = "download-button" class="btn btn-info btn-block"><i class="fa fa-download"></i>Download</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css?v=2') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/waitMe.min.css?v=1.0') }}">
@endsection

@section('javascript')
    <script src="https://cdn.ckeditor.com/ckeditor5/12.3.0/classic/ckeditor.js"></script>
    <script src="{{ asset('assets/js/lib/waitMe.min.js') }}"></script>

    <script>
        var services = <?php echo json_encode($services); ?>;
        var bottomDescription = "{!! addslashes($bottomDescription) !!}";
        @if( isset($editMode) )
            var editMode = true;
            var request = <?php echo json_encode($request); ?>;
        @endif
    </script>

    <script src="{{ asset('assets/js/main.js?v=8') }}"></script>
@endsection
