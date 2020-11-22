var Completed_Websites = {

    statisticsChart : false,
    yearlyChart     : false,

    init: function(){
        Completed_Websites.initDataTable();
        Completed_Websites.initInlineEditForCompletedDate();
        Completed_Websites.initDatePickerStyle();
        Completed_Websites.initChart();
    },

    initDataTable: function(){
        $('#live-website-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [2], type: 'sortme'},
            ],
        });
        $('#no-website-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [2], type: 'sortme'},
            ],
        });
        $('#redirect-website-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        });
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            return $(a).attr('data-value');
        };
    },

    initInlineEditForCompletedDate: function(){
        $("a.completed-at-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-id');
            ajaxUrl = $(element).closest('tr').attr('data-row-type') == 'website' ? '/update-website-attribute' : '/update-task-attribute';
            $(element).editable({
                type        : 'date',
                pk          : websiteId,
                showbuttons : true,
                name        : 'completed_at',
                send        : 'always',
                ajaxOptions : {
                    type : 'POST'
                },
                url         : siteUrl+ajaxUrl,
                mode        : "popup",
                params      : function(params) {
                    params._token = csrf_token;
                    return params;
                },
                display     : function( value ){
                    if( value == "" || value == null )
                        $(this).text('---');
                    else{
                        let dateObj = new Date(value);
                        $(this).text(dateObj.toLocaleDateString('us'));
                    }
                }
            });
        })
        $("a.completed-at-value").on('save', function(e, params) {
            $(this).attr('data-value', params.newValue);
            Completed_Websites._refreshChartData();
        });
    },

    initDatePickerStyle: function(){
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        var styleTag = $('<style type="text/css">td:not(.active)[data-date="' + (UTCToday().getTime()) + '"] { background: #DDD !important; }</style>')
        $('html > head').append(styleTag);
    },

    //Init Chart
    initChart: function(){
        Completed_Websites._refreshChartData();
    },

    //Refresh Chart Data
    _refreshChartData: function(){
        $.ajax({
            type    : 'GET',
            url     : siteUrl + '/website-completed-statistics',
            success : function(data){
                if( data.status == 'success' ){

                    //init bar chart
                    let barChartCanvas = $('#completionBarChart').get(0).getContext('2d')
                    let barChartOptions = {
                        responsive              : true,
                        maintainAspectRatio     : false,
                        datasetFill             : false
                    };
                    if( Completed_Websites.statisticsChart !== false )
                        Completed_Websites.statisticsChart.destroy();
                    Completed_Websites.statisticsChart = new Chart(barChartCanvas, {
                        type: 'bar',
                        data: data.chartData,
                        options: barChartOptions
                    })
                    for( let i = 3; i <= 7; i++){
                        Completed_Websites.statisticsChart.getDatasetMeta(i).hidden=true;
                    }
                    Completed_Websites.statisticsChart.update();

                    //refresh yearly pie chart
                    let pieChartCanvas = $('#yearlyPieChart').get(0).getContext('2d')
                    let pieOptions     = {
                        maintainAspectRatio : false,
                        responsive : true,
                        legend: {
                            display: false
                        }
                    }
                    //Create pie or douhnut chart
                    // You can switch between pie and douhnut using the method below.
                    var pieChart = new Chart(pieChartCanvas, {
                        type: 'pie',
                        data: data.yearlyChartData,
                        options: pieOptions
                    })
                }
            }
        })
    },
};

$(document).ready(function(){
    Completed_Websites.init();
})
