var Failed_Mails_List = {

    deletingSuppressionId: -1,
    init: function(){
        this.initPopover();
        this.initFailedMailActions();
        this.initDatatableForFailedMails();
        this.initInlineEditForSender();
        this.initFilter();
        this.initSuppressionActions();
    },

    initPopover: function(){
        $('[data-toggle="popover"]').popover({
            container: 'body'
        })
        $('body').on('click', function (e) {
            $('[data-toggle=popover]').each(function () {
                // hide any open popovers when the anywhere else in the body is clicked
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    },

    initDatatableForFailedMails: function(){
        Failed_Mails_List.failedMailsTable = $('#failed-mails-table').DataTable({
            "order"     : [[ 0, "desc" ]],
            searching:    true,
			lengthChange: true,
			paging:       true,
            info:         true,

			"processing": true,
			"serverSide": true,
            "ajax" : Failed_Mails_List._getFailedMailsDataTableUrl(),

            "pageLength": 50,
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            "columns": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="' + row.detailViewUrl + '" target="_blank">' + row.timestamp_est + '</a>';
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        return '<strong>' + row.domain + '</strong>';
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        return '<button type="button" class="btn btn-sm ' + (row.severity == 'temporary' ? 'btn-warning' : 'btn-danger') + '"'
                                +'data-toggle="popover"'
                                + 'title="Delivery Status Message"'
                                + 'data-content="' + (row.pretty_message == null ? '' : row.pretty_message) + '">'
                                + row.event
                                + Failed_Mails_List._getFailedMessageType(row.pretty_message)
                                + '</button>';
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        return row.message_from;
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        return row.recipient;
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        if( row.linkedWebsite == null ){
                            return '<a class="website-sender-value" href="#">Find Website</a>';
                        }
                        return '<a href="//' + row.linkedWebsite + '/webadmin" target="_blank">' + row.linkedWebsiteName + '</a>'
                    }
                },
                {
                    "render": function ( data, type, row ) {
                        return '<button type="button" class="btn btn-info archive-mail-btn">Archive</button>';
                    }
                },
            ],
            columnDefs: [
                {targets: [0, 1, 2], orderable : true},
                {targets: [3, 4, 5, 6], orderable : false},
            ],
            "rowCallback": function( row, data ) {
                $(row).attr('data-id', data.id);
                $(row).attr('data-pretty-sender-name', data.pretty_sender_name);
            },
            "createdRow": function ( row, data, index ) {
				$(row).find('[data-toggle="popover"]').popover({
                    container: 'body'
                })
            },
            "drawCallback":function(){
                $("a.website-sender-value").each(function(index, element){
                    let prettySenderName = $(element).closest('tr').attr('data-pretty-sender-name');
                    $(element).editable({
                        type        : 'select',
                        source      : allWebsites,
                        showbuttons : false,
                        onblur      : 'submit',
                        name        : 'mailgun_sender',
                        params      : function(params) {
                            params._token = csrf_token;
                            params.pk = params.value;
                            params.value = prettySenderName;
                            return params;
                        }
                    });
                })

                $("a.website-sender-value").on('save', function(e, params) {
                    location.reload();
                });
            },
        });

        Failed_Mails_List.suppressionTable = $('#supressions-table').DataTable({
            "order"     : [[ 0, "desc" ]],
            searching:    true,
			lengthChange: true,
			paging:       true,
            info:         true,

			"processing": true,
			"serverSide": true,
            "ajax" : Failed_Mails_List._getSuppressionsDataTableUrl(),

            "pageLength": 50,
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            "columns": [
                {
                    data : 'domain'
                },
                {
                    "render": function ( data, type, row ) {
                        if( row.type == 'bounce' )
                            return '<button type="button" class="btn btn-sm bg-purple"'
                                    + 'style="text-transform: capitalize"'
                                    + 'data-toggle="popover"'
                                    + 'title="Suppressed Error"'
                                    + 'data-content="' + row.error +'">'
                                    + row.type
                                    + '</button>';
                        else if( row.type =='compliant' )
                            return '<button type="button" class="btn btn-sm bg-warning"'
                                    + 'style="text-transform: capitalize">'
                                    + row.type
                                    + '</button>';
                        return '';
                    }
                },
                {
                    data: 'address'
                },
                {
                    data: 'timestamp_est'
                },
                {
                    data: 'error'
                },
                {
                    "render": function ( data, type, row ) {
                        return '<button type="button" class="btn btn-info archive-suppression-btn mr-3">Archive</button>'
                                + '<button type="button" class="btn btn-danger delete-suppression-btn">Delete</button>';
                    }
                },
            ],
            columnDefs: [
                {targets: [0, 1, 2, 3], orderable : true},
                {targets: [4, 5], orderable : false},
                {targets: [5], width:"200px"}
            ],
            "rowCallback": function( row, data ) {
                $(row).addClass('suppression-row');
                $(row).attr('data-suppression-id', data.id);
            },
            "createdRow": function ( row, data, index ) {
				$(row).find('[data-toggle="popover"]').popover({
                    container: 'body'
                })
			},
        });
    },

    initFailedMailActions: function(){
        $(document).on('click', '.archive-mail-btn', function(){
            let eventId = $(this).closest('tr').attr('data-id');
            $.ajax({
                type: 'POST',
                url: siteUrl + '/archive-failed-mail',
                data: {
                    _token: csrf_token,
                    eventId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        $.notify('Failed mail log is archived.', { type: 'success' });
                        Failed_Mails_List.failedMailsTable
                            .row( $("tr[data-id='" + eventId + "']") )
                            .remove()
                            .draw();
                    }
                }
            })
        })

        $('.archive-all-mail-btn').click(function(){
            $("#archive-all-mail .domain-value").html(domainFilter == 'all' ? 'All Domains' : domainFilter);
            $("#archive-all-mail").modal('show');
        });
        $("#archive-all-mail .confirm-btn").click(function(){
            $.ajax({
                type: 'POST',
                url: siteUrl + '/archive-all-failed-mail',
                data: {
                    _token: csrf_token,
                    domainFilter
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                        $("#archive-all-mail").modal('hide');
                    }
                }
            })
        })
    },

    initInlineEditForSender: function(){
        //X Editable Options
        $.fn.editable.defaults.send = "always";
        $.fn.editable.defaults.ajaxOptions = {
            type : 'POST'
        };
        $.fn.editable.defaults.url = siteUrl+"/update-website-attribute";
        $.fn.editable.defaults.mode = 'inline';
    },

    initFilter: function(){

        $("#domain-filter").select2({});

        $("#domain-filter").change(function(){
            location.href = siteUrl + '/failed-mails?domain=' + $(this).val()
        });
    },

    initSuppressionActions: function(){
        $(document).on('click', '.suppression-row .delete-suppression-btn', function(){
            Failed_Mails_List.deletingSuppressionId = $(this).closest('.suppression-row').attr('data-suppression-id');
            $("#delete-suppression-modal").modal('show');
        });

        $("#delete-suppression-modal .confirm-btn").click(function(){
            $.ajax({
                type : 'POST',
                url : siteUrl + '/delete-mailgun-suppression',
                data : {
                    _token: csrf_token,
                    suppressionId: Failed_Mails_List.deletingSuppressionId
                },
                success: function(data){
                    $("#delete-suppression-modal").modal('hide');
                    if( data.status == 'success' ){
                        $.notify('Failed mail log is archived.', { type: 'success' });
                        Failed_Mails_List.suppressionTable
                            .row( $("tr.suppression-row[data-suppression-id='" + Failed_Mails_List.deletingSuppressionId + "']") )
                            .remove()
                            .draw();
                    }
                }
            });
        })

        //Archive Suppression Action
        $(document).on('click', '.suppression-row .archive-suppression-btn', function(){
            Failed_Mails_List.deletingSuppressionId = $(this).closest('.suppression-row').attr('data-suppression-id');

            $.ajax({
                type : 'POST',
                url : siteUrl + '/archive-mailgun-suppression',
                data : {
                    _token: csrf_token,
                    suppressionId: Failed_Mails_List.deletingSuppressionId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        $.notify('Suppression is archived.', { type: 'success' });
                        Failed_Mails_List.suppressionTable
                            .row( $("tr.suppression-row[data-suppression-id='" + Failed_Mails_List.deletingSuppressionId + "']") )
                            .remove()
                            .draw();
                    }
                }
            });
        });

        $('.archive-all-suppression-btn').click(function(){
            $("#archive-all-suppression .domain-value").html(domainFilter == 'all' ? 'All Domains' : domainFilter);
            $("#archive-all-suppression").modal('show');
        });
        $("#archive-all-suppression .confirm-btn").click(function(){
            $.ajax({
                type: 'POST',
                url: siteUrl + '/archive-all-mailgun-suppression',
                data: {
                    _token: csrf_token,
                    domainFilter
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                        $("#archive-all-suppression").modal('hide');
                    }
                }
            })
        })
    },

    /**
	 * Generate datatable url for failed events
	 * @private
	 */
	_getFailedMailsDataTableUrl: function(){
		return siteUrl + '/get-mailgun-events-datatable?domainFilter=' + domainFilter;
    },

    /**
	 * Generate datatable url for suppressions
	 * @private
	 */
	_getSuppressionsDataTableUrl: function(){
		return siteUrl + '/get-mailgun-suppressions-datatable?domainFilter=' + domainFilter;
    },

    _getFailedMessageType: function(message){
        if( message == null )  return '';
        if( message.toLowerCase().includes('The email account that you tried to reach does not exist'.toLowerCase()) )
            return '- Invalid Email';
        else if( message.toLowerCase().includes('The email account that you tried to reach is over quota'.toLowerCase()) )
            return '- Over Quota';
        return '';
    }
}
$(document).ready(function(){
    Failed_Mails_List.init();
})
