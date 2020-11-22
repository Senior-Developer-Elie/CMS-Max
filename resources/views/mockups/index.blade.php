@extends('layouts.theme')

@section('content')
    <div id="content" class="container">
        <div class="form-comment-warpper text-center">
            <p>Just upload an image, select a few basic parameters, and CMS Max Website Mockup will generate a virtual homepage design in detailed responsive format.  Your design will get its own unique link for sharing, which will not be displayed publicly.</p>
        </div>
        <form enctype="multipart/form-data" method="post" id="goForm" name="goForm">
            @csrf
            <div class="row">

                <div class="col-md-6 col-sm-12 d-flex step-widget bottom-splitter">
                    <div class="numberCircle">1</div>
                    <div class="step-wrapper">
                        <div class="step-title">
                            <label>Upload Images:</label>
                        </div>
                        <div class="step-content">
                            <button id = "file-upload-button" type = "button" class="button blue mb-1">Choose Files</button>
                            <label id="file-choosen-text">No file choosen</label><br>
                            <input name="image" id="image-file" type="file" size="50" accept="image/*" style = "display:none;" multiple />
                            <label>(Required - images only: .jpg, .png, .gif | Max file size: 5mb)</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 d-flex">
                    <div class="step-widget right">
                        <div class="d-flex bottom-splitter">
                            <div class="numberCircle">2</div>
                                <div class="step-wrapper">
                                    <div class="step-title">
                                        <label>Mockup name/title:</label>
                                    </div>
                                    <div class="step-content">
                                        <input class="box" type="text" id="title" name="title" value=""
                                            placeholder="Your Mockup Title" />
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 d-flex step-widget bottom-splitter">
                    <div class="numberCircle">3</div>
                    <div class="step-wrapper">
                        <div class="step-title d-flex align-items-center">
                                <input name="togglecolour" type="checkbox" value="yes" id="togglecolour" />
                                <label for = "togglecolour" class="ml-3">I&quot;ll choose a background colour</label class="ml-2">
                        </div>
                        <div id="bgcolour" class="step-content" style="display:none">
                            <div class="form-item">
                                <label for="color">Background colour:</label>
                                <input class="ml-3" type="text" size="8" id="color" name="color" value="#FFFFFF" />
                            </div>
                            <div id="picker" class="mt-4"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 d-flex">
                    <div class="step-widget right">
                        <div class="d-flex bottom-splitter">
                            <div class="numberCircle">4</div>
                                <div class="step-wrapper">
                                    <div class="align-wrapper d-flex align-items-center">
                                        <input id="aligncentre" name="align" type="radio" value="center" checked="checked" />
                                        <img src="{{ asset('assets/images/text_align_center.png') }}" alt="Right align" />
                                        <label for="aligncentre"><span>Align centre</span></label>
                                    </div>
                                    <div class="align-wrapper d-flex align-items-center">
                                        <input id="alignleft" name="align" type="radio" value="left" />
                                        <img src="{{ asset('assets/images/text_align_left.png') }}" alt="Left align" width="16" height="16" />
                                        <label for="alignleft">
                                            <span>Align left</span>
                                        </label>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="step-title d-flex align-items-center">
                        <input name="toggleEmailBox" type="checkbox" value="yes" id="toggleEmailBox" class="mr-3"/>
                        <label for="toggleEmailBox">Send a link of the mock up by email</label>
                    </div>
                    <div id="emailBox" class="step-content mt-2" style="display:none">
                        <input type="text" size="45" id="myemail" name="myemail"
                            placeholder="Enter email address">
                    </div>
                </div>
            </div>

            <div class="submit-button-wrapper mt-4">
                <button type="button" name="Submit" id="submit" class="button blue mb-3">Show me my mockup &raquo;</button>
            </div>
        </form>
    </div>
@endsection


@section('css')
    <link href="{{ asset('assets/css/lib/farbtastic/farbtastic.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/lib/toastr.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/mockups/button.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/mockups/main.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('javascript')
    <script src="{{ asset('assets/js/lib/farbtastic.js') }}"></script>
    <script src="{{ asset('assets/js/lib/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/mockups/main.js') }}"></script>
@endsection
