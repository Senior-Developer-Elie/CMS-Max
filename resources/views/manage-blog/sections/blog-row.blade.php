<tr>
    <td>
        {{ (new \Carbon\Carbon($blog->desired_date))->format('M Y') }}
    </td>
    <td>
        {{ $blog->name }}
    </td>
    @if( $blog->website() && $blog->website()->client() )
        <td>
            <a href = "{{ url('/client-history?clientId=' . $blog->website()->client()->id) }}">
                {{ $blog->website()->client()->name }}
            </a>
        </td>
        <td>
            {{ $blog->website()->name }}
        </td>
        <td>
            <a href = "//{{ $blog->website()->website }}" target="_blank">
                {{ $blog->website()->website }}
            </a>
        </td>
        <td>
            {{ $blog->website()->target_area }}
        </td>
    @else
        <td></td>
        <td></td>
        <td></td>
    @endif
    <td>
        <div class="btn-group">
            @if( is_null($blog->blog_url) || $blog->blog_url == '' )
                 <div class="btn-group">
                    <button type="button" class="btn btn-info btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item upload-blog-button" href="#" data-blog-id="{{ $blog->id }}">Upload Blog</a>
                        <a class="dropdown-item" href="{{ url('change-to-not-available/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}">Change Blog Month to N/A</a>
                    </div>
                    <a href="#" class="upload-blog-button" data-blog-id="{{ $blog->id }}">
                        <button type="button" class="btn btn-info btn-flat">Pending To Write</button>
                    </a>
                </div>
            @elseif( is_null($blog->blog_image) || $blog->blog_image == '' )
                <div class="btn-group">
                    <button type="button" class="btn bg-teal btn-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item upload-image-button" href="#" data-blog-id="{{ $blog->id }}">Upload Image</a>
                        <a target = "_blank" class="dropdown-item" href = "{{ url('download-blog/' . $blog->id . '/blog') }}">Download Blog</a>
                        <a class="dropdown-item" href="{{ url('clear-upload/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}" >Remove Blog</a>
                        <a class="dropdown-item" href="{{ url('change-to-not-available/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}" >Change Blog Month to N/A</a>
                    </div>

                    <a href="#" class="upload-image-button" data-blog-id="{{ $blog->id }}">
                        <button type="button" class="btn bg-teal btn-primary btn-flat">Pending To Add Image</button>
                    </a>
                </div>
            @elseif( !$blog->marked )
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item clear-blog-image-button" href="#" data-blog-id="{{ $blog->id }}">Revert Back to Pending</a>
                        <a class="dropdown-item upload-blog-button" href="#" data-blog-id="{{ $blog->id }}">Upload & Overwrite Blog</a>
                        <a class="dropdown-item upload-image-button" href="#" data-blog-id="{{ $blog->id }}">Upload & Overwrite Image</a>
                        <a class="dropdown-item" target = "_blank" href = "{{ url('download-blog/' . $blog->id . '/blog') }}">
                            Download Blog
                        </a>
                        <a class="dropdown-item" target = "_blank" href = "{{ url('download-blog/' . $blog->id . '/image') }}">
                            Download Image
                        </a>
                        <a class="dropdown-item" href="{{ url('mark-complete/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}">
                            Mark As Completed
                        </a>
                        <a class="dropdown-item" href="{{ url('change-to-not-available/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}">
                            Change Blog Month to N/A
                        </a>
                    </div>

                    <a target = "_blank" href = "{{ url('download-blog/' . $blog->id . '/both') }}">
                        <button type="button" class="btn btn-warning btn-flat">Download Blog & Image</button>
                    </a>
                </div>
            @else
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item clear-blog-image-button" href="#" data-blog-id="{{ $blog->id }}">Revert Back to Pending</a>
                        <a class="dropdown-item" href="{{ $blog->blog_website }}" target="_blank">
                            View Blog Url
                        </a>
                        <a class="dropdown-item" href="{{ url('undo-complete/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}">
                            Undo Completed Status
                        </a>
                        <a class="dropdown-item" href="{{ url('clear-upload/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}">
                            Clear File Uploaded
                        </a>
                        <a class="dropdown-item" href="{{ url('change-to-not-available/' . $blog->id . '?backUrl=' . Request::getRequestUri()) }}">
                            Change Blog Month to N/A
                        </a>
                    </div>

                    <a href="{{ $blog->blog_website }}" target="_blank">
                        <button type="button" class="btn btn-success btn-flat">
                            <i class="fa fa-fw fa-check"></i>Completed {{ !is_null($blog->completed_by()) ? ('By ' . $blog->completed_by()->name) : ''}}
                        </button>
                    </a>
                </div>
            @endif
        </div>
    </td>
</tr>
