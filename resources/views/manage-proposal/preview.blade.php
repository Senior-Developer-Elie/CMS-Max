<html>
    @php
        $templateContent = \App\Services\ProposalTemplateService::getTemplateContent($templateType);    
    @endphp
    <body>
        <div id = "mainWrapper">
            <div class = "logo">
                <img src= "{!!  $templateContent['logo']  !!}"/>
            </div>

            <h4 class = "text-center top-title">Building & Branding Custom Websites that Get Results!</h4>
            <h4 class = "text-center top-title">Proposal Presented by Evolution Marketing, Inc. to {{ $clientName ? $clientName : '' }} on {{ Carbon\Carbon::now()->isoFormat('MMM D, Y') }}</h4>
            <h4 class = "text-center top-title">Summary of Service Fees for Build and Maintenance of <span class="website-url">{{ $websiteUrl ? $websiteUrl : '' }}</span></h4>

            @if( count($oneTimeServices) > 0 )
                <div class = "service-wrapper">
                    <h4 class = "service-main-title">Set-Up Fees (One-Time Charges)*:</h4>
                    <table class = "service-table">
                        <thead>
                            <tr>
                                <th>
                                    Product/Service
                                </th>
                                <th width="125px">
                                    Rate (One-Time)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($oneTimeServices as $service)
                                <tr>
                                    <td>
                                        <div class = "service-single-wrapper">
                                            {!! $service['content'] !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class ="price">${{ $service['price'] }} (one-time)</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif



            @if( count($recurringServices) > 0 )
                <div class = "service-wrapper">
                    <h4 class = "service-main-title">Maintenance Fees (Monthly Recurring Charges)**:</h4>
                    <table class = "service-table">
                        <thead>
                            <tr>
                                <th>
                                    Product/Service
                                </th>
                                <th width="125px">
                                    Rate
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recurringServices as $service)
                                <tr>
                                    <td>
                                        <div class = "service-single-wrapper">
                                            {!! $service['content'] !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class ="price">${{ $service['price'] }} (monthly)</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="fees-wrapper">
                                <td>
                                    <div class = "service-single-wrapper">
                                        <h4 class="service-single-title">Total Maintenance Fees</h4>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class ="price">${{ $totalRecurringFee }} (monthly)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            <div class = "bottom-description mt-3">
                {!! $bottomDescription ? $bottomDescription : ''  !!}
            </div>

            @if( isset($addSignature) AND $addSignature == 'on' )
                @if( !isset($emailContact) ||  $emailContact != 'on' || $requestType == 'preview' || $requestType == 'normal-download' || ( $requestType == 'admin-download' && $status == 'not-signed' ) )
                    <div class="signature-wrapper">
                        <div class="signature-line">&nbsp;</div>
                        <div class="signature-line-description">Full Name</div>
                        <div class="signature-line">&nbsp;</div>
                        <div class="signature-line-description">Job Title</div>
                        <div class="signature-line">&nbsp;</div>
                        <div class="signature-line-description">Signature</div>
                    </div>
                @elseif( isset($emailContact) &&  $emailContact == 'on' && $requestType == 'sign' )
                    <form id = "sign-form" method = "POST" action="{{ url("client-signed") }}">
                        @csrf
                        <div class="signature-wrapper">
                            <div class="d-flex justify-content-center"><input class = "form-control" type="text" name="fullName" placeholder="Full Name" required></div>
                            <div class="d-flex justify-content-center"><input class = "form-control" type="text" name="jobTitle" placeholder="Job Title" required></div>
                            <input id = "signature-field" type = "hidden" name = "signature">
                            <input type = "hidden" name = "proposalId" value="{{ $proposalId }}">
                            <div class="d-flex justify-content-center"><canvas id = "sign-pad"></canvas></div>
                            <div class="d-flex justify-content-center"><button id = "submit-button" class = "btn" type = "submit" >Submit</button></div>
                        </div>
                    </form>
                @elseif( isset($emailContact) &&  $emailContact == 'on' && $requestType == 'admin-download' && $status == 'signed' )
                    <div class="signature-wrapper">
                        <h3 class="signature-line">{{ $fullName }}</h3>
                        <div class="signature-line-description">Full Name</div>
                        <h3 class="signature-line">{{ $jobTitle }}</h3>
                        <div class="signature-line-description">Job Title</div>
                        <div class="signature-line last"><img src = "{{ $signature }}"></div>
                        <div class="signature-line-description">Signature</div>
                    </div>
                @endif
            @endif

        </div>

        @if( isset($emailContact) &&  $emailContact == 'on' && $requestType == 'sign' )
            <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
            <script src="{{ asset('assets/js/lib/signature_pad.min.js') }}"></script>
            <script src="{{ asset('assets/js/sign-page.js') }}"></script>
        @else
            <footer>
                <span class = "prepared-by">Prepared by: {{ $templateContent['prepared_by'] }}</span>
                <span class = "phone-span">Phone: {{ $templateContent['phone'] }}</span>
                <span class = "emaila">Email: {{ $templateContent['email'] }}</span>
                <span>&nbsp;</span>
            </footer>
        @endif
    </body>
</html>

        <style>
            body{
                margin: 0 auto;
                margin-top: 50px;
                width: 610px;
                padding-bottom: 0;
            }
            html{
                padding: 0;
                margin: 0;
            }
            .text-center{
                text-align: center;
            }
            .mt-2{
                margin-top: 12px;
            }
            .mt-3{
                margin-top: 25px;
            }
            .d-flex{
                display: flex;
            }
            .justify-content-center{
                justify-content: center;
            }
            body p{
                margin: 0;
            }
            .logo{
                text-align: center;
            }
            .logo img{
                height: 80px;
            }
            .top-title{
                margin: 5px 0;
                font-weight: normal;
            }
            .website-url{
                color: rgb(41, 132, 196)
            }
            .service-wrapper{
                margin-top: 15px;
            }
            .service-wrapper .service-main-title{
                font-style: italic;
                margin: 5px 0;
            }
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
            }
            table.service-table{
                width: 100%;
            }
            table.service-table > thead >tr > th{
                text-align: left;
            }
            table.service-table th, table.service-table td{
                padding: 5px 4px;
            }
            table.service-table thead{
                background: #93ebfc;
            }
            .service-single-wrapper{
                font-size: 13px;
            }
            .service-single-wrapper .service-single-title{
                margin: 5px 5px;
            }
            .service-single-wrapper ul{
                margin: 0;
            }
            table.service-table .price{
                
            }
            .fees-wrapper, .fees-wrapper .service-single-wrapper .service-single-title{
                font-size: 16px;
            }

            .bottom-description{
                font-size: 13px;
            }

            .signature-wrapper{
                margin-top: 25px;
            }
            .signature-wrapper .signature-line{
                width: 220px;
                border-bottom: solid 2px grey;
                font-size: 20px;
            }
            .signature-wrapper div.signature-line{
                height: 60px;
            }
            .signature-wrapper h3.signature-line{
                margin: 0;
            }
            .signature-wrapper .signature-line img{
                height: 100%;
            }
            .signature-wrapper input{
                max-width: 200px;
                margin-top: 15px;
            }
            .signature-wrapper #sign-pad{
                border: solid 1px;
                margin-top: 30px;
                margin-bottom: 20px;
                display: block;
            }
            .signature-wrapper .signature-line-description{
                margin: 5px 0 10px 5px;
                color: #777777;
            }
            .btn:not(:disabled):not(.disabled){
                cursor: pointer;
                color: #fff;
                background-color: #117a8b;
                border-color: #10707f;
            }
            .btn:hover {
                color: #fff;
                background-color: #138496;
                border-color: #117a8b;
            }
            .btn{
                display: inline-block;
                font-weight: 400;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                border: 1px solid transparent;
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                border-radius: .25rem;
                transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            }

            .form-control {
                display: block;
                width: 100%;
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            }

            footer .prepared-by{
                display: inline-block;
                margin-left: 0;
            }
            footer .phone-span{
                display: inline-block;
                margin-left: 30px;
            }
            footer .emaila{
                display: inline-block;
                margin-left: 30px;
            }

            footer {
                position: fixed;
                bottom: 0px;
                left: 55px;
                right: 82px;
                height: 50px;
            }
            .page-break {
                page-break-after: always;
            }
        </style>
    </body>
</html>
