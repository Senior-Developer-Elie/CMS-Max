<table class="task-list-table table" style="width:100%">
    <thead>
        <tr>
            <th width="30px">
            </th>
            <th class="sortable-column {{ $sortColumn == 'title' ? ( $sortOrder == 'asc' ? 'sorting_asc' : 'sorting-desc' ) : '' }}" data-sort-column='title'>Name</th>
            <th width="150px" class="sortable-column {{ $sortColumn == 'created_at' ? ( $sortOrder == 'asc' ? 'sorting_asc' : 'sorting-desc' ) : '' }}" data-sort-column='created_at'>Date Created</th>
            <th width="150px" class="sortable-column {{ $sortColumn == 'due_date' ? ( $sortOrder == 'asc' ? 'sorting_asc' : 'sorting-desc' ) : '' }}" data-sort-column='due_date'>Due Date</th>
            <th class="sortable-column {{ $sortColumn == 'website_id' ? ( $sortOrder == 'asc' ? 'sorting_asc' : 'sorting-desc' ) : '' }}" data-sort-column='website_id'>Website</th>
            <th class="sortable-column {{ $sortColumn == 'assignee_id' ? ( $sortOrder == 'asc' ? 'sorting_asc' : 'sorting-desc' ) : '' }}" data-sort-column='assignee_id'>Assignee</th>
            <th width="200px">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $innerBlogs as $innerBlog )
            @if( ($job_status == 'to-do' && $innerBlog->to_do == 1) || ($job_status == 'on-hold' && $innerBlog->to_do == false) )
                <?php
                    $status = $innerBlog->status();
                    $label  = is_null($innerBlog->website()) ? '' : $innerBlog->website()->name;
                ?>
                <tr class="inner-page-item {{ $status }}" data-inner-page-id="{{ $innerBlog->id }}">
                    <td class = "text-center" >
                        @if( $enableDrag == 'on' && Auth::user()->can('content manager') && $innerBlog->to_do == true && $sortColumn == 'priority' )
                            <span class="handle d-flex">
                                <i class="fa fa-ellipsis-v"></i>
                                <i class="fa fa-ellipsis-v"></i>
                            </span>
                        @endif
                    </td>
                    <td class="inner-blog-title">
                        {{ $innerBlog->title }}
                    </td>
                    <td>
                        {{ (new \Carbon\Carbon($innerBlog->created_at))->format('m/d/y') }}
                    </td>
                    <td class="due-date-text">
                        {{ empty($innerBlog->due_date) ? '' : (new \Carbon\Carbon($innerBlog->due_date))->format('m/d/y') }}
                    </td>
                    <td class="website">
                        @if( $status == 'done' )
                            <a href="{{ $innerBlog->website[0] }}" target="_blank"> {{ $label }}</a>
                        @else
                            <a href="//{{ is_null($innerBlog->website()) ? '' : $innerBlog->website()->website }}" target="_blank">
                                {{ $label }}
                            </a>
                        @endif
                    </td>
                    <td class="assignee-name">
                        @if( is_null($innerBlog->assignee()) )
                            <div class="DomainUserAvatar">
                                <div class="AvatarPhoto" style="background-image: url('assets/images/default-avatar.jpg');">
                                </div>
                            </div>
                            <div class="AssigneeWithName-name">No Assignee</div>
                        @elseif( empty($innerBlog->assignee()->avatar) )
                            <div class="DomainUserAvatar">
                                <div class="AvatarPhoto">
                                    {{ $innerBlog->assignee()->getInitials() }}
                                </div>
                            </div>
                            <div class="AssigneeWithName-name">
                                {{ $innerBlog->assignee()->name }}
                            </div>
                        @else
                            <div class="DomainUserAvatar">
                                <div class="AvatarPhoto" style="background-image: url('{{ $innerBlog->assignee()->getPublicAvatarLink() }}'); background-color:white;"></div>
                            </div>
                            <div class="AssigneeWithName-name">
                                {{ $innerBlog->assignee()->name }}
                            </div>
                        @endif
                    </td>
                    <td class="tools">
                        @can('delete ability')
                            <span class="tool-button">
                                <i class="far fa-trash-alt delete-inner-page-button" data-toggle="tooltip" data-placement="top" title="Delete Task"></i>
                            </span>
                        @endcan
                        <span class="tool-button">
                            <i class="fa fa-edit edit-inner-page-button" data-toggle="tooltip" data-placement="top" title="Edit Task"></i>
                        </span>
                        @if( $status == 'done' )
                            <span class="tool-button">
                                <i class="fas fa-arrow-circle-left undo-complete-button" data-toggle="tooltip" data-placement="top" title="Undo Completed Status"></i>
                            </span>
                        @endif
                        @if( $status != 'done' )
                            <span class="tool-button">
                                <i class="fas fa-check complete-button" data-toggle="tooltip" data-placement="top" title="Complete"></i>
                            </span>
                        @endif
                        @if( count($innerBlog->files()->get()) > 0 )
                            <span class="tool-button">
                                <i class="fa fa-download download-button" data-toggle="tooltip" data-placement="top" title="Download"></i>
                            </span>
                        @endif
                    </td>
                </tr>
                <tr class = "inner-page-item-description" style="display:none;">
                    <td colspan="7">
                        @if( $status == 'done' )
                            <h5 class="sub-text-in-list">Complete Urls</h5>
                            <ul>
                                @if( is_array($innerBlog->website) )
                                    @foreach ($innerBlog->website as $url)
                                        <li>
                                            <a href="{{ $url }}" target = "_blank">
                                                {{ $url }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        @endif
                        @if( $status == 'done' && strlen($innerBlog->needed_text) > 0 )
                            <h5 class="sub-text-in-list">Job Content</h5>
                        @endif
                        {!! $innerBlog->needed_text !!}
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<!--
<ul id="inner-pages-list" class="todo-list">
    @foreach ( $innerBlogs as $innerBlog )
        @if( ($job_status == 'to-do' && $innerBlog->to_do == 1) || ($job_status == 'on-hold' && $innerBlog->to_do == false) )
            <?php
                $status = $innerBlog->status();
                $label  = is_null($innerBlog->website()) ? '' : $innerBlog->website()->name;
            ?>
            <li class="inner-page-item {{ $status }}" data-inner-page-id="{{ $innerBlog->id }}">
                @can("content manager")
                    @if( $job_status == 'to-do' && $sortColumn == 'priority')
                        <span class="handle d-flex">
                            <i class="fa fa-ellipsis-v"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                    @endif
                @endcan

                <strong class="completed-date">{{ $status == "done" ? ((new \Carbon\Carbon($innerBlog->completed_at))->format('m/d/Y h:i a') . ", ") : "" }}</strong>

                @if( !is_null($innerBlog->assignee()) )
                    <label class = "assignee-name">
                        {{ $innerBlog->assignee()->name }},
                    </label>
                @endif

                <label class="label inner-blog-status-label">
                    @if( $status == 'done' )
                        <a href="{{ $innerBlog->website[0] }}" target="_blank"> {{ $label }}</a>
                    @else
                        <a href="//{{ is_null($innerBlog->website()) ? '' : $innerBlog->website()->website }}" target="_blank">
                            {{ $label }}
                        </a>
                    @endif
                </label>

                <div class="text">
                    {{ $innerBlog->title }}
                </div>

                <div class="tools">

                    @if( count($innerBlog->files()->get()) > 0 )
                        <i class="fa fa-download download-button" data-toggle="tooltip" data-placement="top" title="Download"></i>
                    @endif

                    @if( $status != 'done' )
                        <i class="fas fa-check complete-button" data-toggle="tooltip" data-placement="top" title="Complete"></i>
                    @endif

                    @if( $status == 'done' )
                        <i class="fas fa-arrow-circle-left undo-complete-button" data-toggle="tooltip" data-placement="top" title="Undo Completed Status"></i>
                    @endif

                    <i class="fa fa-edit edit-inner-page-button" data-toggle="tooltip" data-placement="top" title="Edit Task"></i>
                    @can('delete ability')
                        <i class="far fa-trash-alt delete-inner-page-button" data-toggle="tooltip" data-placement="top" title="Delete Task"></i>
                    @endcan
                </div>
            </li>
            <div class="inner-page-item-description" style="display:none">
                @if( $status == 'done' )
                    <h5 class="sub-text-in-list">Complete Urls</h5>
                    <ul>
                        @if( is_array($innerBlog->website) )
                            @foreach ($innerBlog->website as $url)
                                <li>
                                    <a href="{{ $url }}" target = "_blank">
                                        {{ $url }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                @endif
                @if( $status == 'done' && strlen($innerBlog->needed_text) > 0 )
                    <h5 class="sub-text-in-list">Job Content</h5>
                @endif
                {!! $innerBlog->needed_text !!}
            </div>
        @endif
    @endforeach
</ul>
-->
