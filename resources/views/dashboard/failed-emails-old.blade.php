<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger">
              <h3 class="card-title">Failed Mailgun Emails : <span id="total-failed-logs-count">{{ count($failedMails) }}</span></h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table m-0" id="failed-mails-table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Event</th>
                                <th>Severity</th>
                                <th>Suppressed</th>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Website Name</th>
                                <th width="80pxs">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $failedMails as $failedMail )
                                <tr data-id="{{ $failedMail->getId() }}" data-pretty-sender-name="{{ $failedMail->prettySender }}">
                                    <td>
                                        <a data-value="{{ $failedMail->getTimestamp() }}" href="{{ $failedMail->detailViewUrl }}" target="_blank">
                                            {{ $failedMail->getEventDate()->format('m/d/Y h:i A') }}
                                        </a>
                                    </td>
                                    <td>
                                        <?php $failedMessage = empty($failedMail->getDeliveryStatus()['message']) ? $failedMail->getDeliveryStatus()['description']:$failedMail->getDeliveryStatus()['message']; ?>
                                        <button type="button" class="btn btn-sm {{ $failedMail->getSeverity() == 'temporary' ? 'btn-warning' : 'btn-danger'}}"
                                            data-toggle="popover"
                                            title="Delivery Status Message"
                                            data-content="{{ $failedMessage }}">
                                            {{ $failedMail->getEvent() }}
                                            @if( stripos($failedMessage, 'The email account that you tried to reach does not exist') != FALSE )
                                             - Invalid Email
                                            @elseif( stripos($failedMessage, 'The email account that you tried to reach is over quota') != FALSE)
                                             - Over Quota
                                            @endif
                                        </button>
                                    </td>
                                    <td>
                                        {{ $failedMail->getSeverity() }}
                                    </td>
                                    <td>
                                        @if( $failedMail->suppressed !== FALSE )
                                            <button type="button" class="btn btn-sm bg-purple"
                                                data-toggle="popover"
                                                title="Suppressed Error"
                                                data-content="{!! $failedMail->suppressed->getError() !!}">
                                                Suppressed
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $failedMail->getMessage()['headers']['from'] }}
                                    </td>
                                    <td>
                                        {{ $failedMail->getRecipient() }}
                                    </td>
                                    <td>
                                        @if( is_null($failedMail->linkedWebsite) )
                                            <a class="website-sender-value">
                                                Find Website
                                            </a>
                                        @else
                                            <a href="//{{ getCleanUrl($failedMail->linkedWebsite->website) }}/webadmin" target="_blank">
                                                {{ $failedMail->linkedWebsite->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info archive-mail-btn">Archive</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
    </div>
</div>
