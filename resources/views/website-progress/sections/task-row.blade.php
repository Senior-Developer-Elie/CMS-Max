<div class="TaskRow" data-task-id="{{ $task === false ? "" : $task->id }}"
    {{ $task === false ? "style=display:none" : "" }}
    {{ $task === false ? "id=sample-task-row" : "" }}>
    <svg class="MiniIcon TaskRow-DragIcon" viewBox="0 0 24 24" visibility="hidden"><path d="M10,4c0,1.1-0.9,2-2,2S6,5.1,6,4s0.9-2,2-2S10,2.9,10,4z M16,2c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S17.1,2,16,2z M8,10 c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S9.1,10,8,10z M16,10c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S17.1,10,16,10z M8,18 c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S9.1,18,8,18z M16,18c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S17.1,18,16,18z"></path></svg>
    <div class="TaskCell TaskNameCell">
        <div class="GridCell WithoutLeftBorder">
            <label class="task-name" data-value="{{ $task === false ? "" : $task->name }}">
            </label>
            <div class="task-status-wrapper">
                <span class="count-with-icon comment-count" {{ ($task !== false && count($task->comments()->get()) > 0) ? '' : 'style=display:none;' }}>
                    <span class="value">{{ ($task !== false && count($task->comments()->get()) > 0) ? count($task->comments()->get()) : '' }}</span>
                    <svg class="MiniIcon" viewBox="0 0 24 24"><path d="M4.2,24.1c-0.2,0-0.3,0-0.5-0.1c-0.3-0.2-0.5-0.5-0.5-0.9v-5.2C1.1,16.1,0,13.7,0,11c0-5,4-9,9-9h6c5,0,9,4,9,9 c0,5-4,9-9,9h-4.1l-6.3,3.9C4.5,24,4.3,24.1,4.2,24.1z M9,4c-3.9,0-7,3.1-7,7c0,2.2,1,4.2,2.8,5.6C5,16.8,5.2,17,5.2,17.4v3.9 l5-3.1c0.2-0.1,0.3-0.2,0.5-0.2H15c3.9,0,7-3.1,7-7c0-3.9-3.1-7-7-7H9z"></path></svg>
                </span>
                <span class="count-with-icon attachment-count" {{ ($task !== false && count($task->files()->get()) > 0) ? '' : 'style=display:none;' }}>
                    <span class="value">{{ ($task !== false && count($task->files()->get()) > 0) ? count($task->files()->get()) : '' }}</span>
                    <svg class="MiniIcon" viewBox="0 0 24 24"><path d="M20,15c-1.9,0-3.4,1.3-3.9,3H7c-2.8,0-5-2.2-5-5v-3h14.1c0.4,1.7,2,3,3.9,3c2.2,0,4-1.8,4-4s-1.8-4-4-4 c-1.9,0-3.4,1.3-3.9,3H2V3c0-0.6-0.4-1-1-1S0,2.4,0,3v10c0,3.9,3.1,7,7,7h9.1c0.4,1.7,2,3,3.9,3c2.2,0,4-1.8,4-4S22.2,15,20,15z M20,7c1.1,0,2,0.9,2,2s-0.9,2-2,2s-2-0.9-2-2S18.9,7,20,7z M20,21c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S21.1,21,20,21z"></path></svg>
                </span>
                <span class="detailsButton">
                    Details
                    <svg class="MiniIcon ArrowRightMiniIcon SpreadsheetTaskNameCell-detailsIcon" viewBox="0 0 24 24">
                        <path d="M8.9,20.4c-0.4,0-0.7-0.1-1-0.4c-0.6-0.6-0.7-1.5-0.1-2.1l5.2-5.8L7.8,6C7.3,5.4,7.3,4.4,8,3.9C8.6,3.3,9.5,3.4,10.1,4l6.1,7.1c0.5,0.6,0.5,1.4,0,2l-6.1,6.8C9.8,20.3,9.4,20.4,8.9,20.4z"></path>
                    </svg>
                </span>
            </div>
        </div>
    </div>
    <div class="TaskCell GridCell AssigneeCell">
        <a href="#" class="assignee-value" data-value="{{ $task === false ? '' : (is_null($task->assignee()) ? '' : $task->assignee()->id)  }}">
            <div class="DomainUserAvatar">
                <div class="AvatarPhoto"></div>
            </div>
            <div class="AssigneeWithName-name"></div>
        </a>
    </div>
    <div class="TaskCell GridCell ProgressCell text-center">
        <span class="pre-live-check-count">{{ $task === false ? "" : (is_null($task->pre_live) ? 0 : count($task->pre_live)) }}/16</span>
    </div>
    @if( $task !== false && $task->completed )
        <div class="TaskCell GridCell CompletedAtCell text-center">
            <a href="#" class="completed-at-value" data-value="{{ $task === false ? '' : $task->completed_at  }}">
            </a>
        </div>
    @endif
</div>
