var Profit_Loss = {

    init: function(){
        this.initActions();
        this.calculateProfit();

        //For Blog Style
        $(".expense-row[data-blog-row=true]").find(".confirm-btn").trigger('click');
    },

    initActions: function(){

        //Click expense value action
        $(".expense-row .expense-value-wrapper").click(function(e){
            e.stopPropagation();
            $(this).find(".expense-value").hide();
            $(this).find(".expense-value-input").show();
            $(this).find(".expense-value-input").focus();
            $(this).find(".expense-value-input").select();
        })

        //Confirm Action
        $(".expense-row .confirm-btn").click(function(e){
            e.stopPropagation();
            let value = parseFloat($(this).closest(".expense-row").find(".expense-value-input").val());
            let prettyString = "";
            if( $(this).closest(".expense-row").attr("data-blog-row") == "true" )
                prettyString = " ($" + value.toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }) + " &#10005; " + blogCount + ")" + "$" + (value*blogCount).toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 });
            else
                prettyString = "$" + value.toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 });

            $(this).closest(".expense-row").find(".expense-value").html(prettyString);
            $(this).closest(".expense-row").find(".expense-value").show();
            $(this).closest(".expense-row").find(".expense-value-input").hide();

            Profit_Loss.calculateProfit();
        })

        //Confirm Trigger By Enter
        $(".expense-row .expense-value-input").keypress(function(e){
            if( e.which == 13 ) {
                e.preventDefault();
                $(this).closest(".expense-row").find('.confirm-btn').trigger('click');
            }
        });

        //Confirm Trigger By outsid cliking
        $(document).click(function(){
            //Find all visible confirm buttons and confirm trigger
            $(".expense-row .expense-value-input:visible").each(function(index, element){
                $(element).closest(".expense-row").find(".confirm-btn").trigger('click');
            })
        });

        $(".expense-row .expense-value-input").click(function(e){
            e.stopPropagation();
        })

        //Target Month Change Action
        $("#target-month-select").change(function(){
            location.href = siteUrl + "/profit-loss?targetHistory=" + $(this).val();
        });

        //
        $("#save-profit-loss-btn").click(function(){
            let ajaxData = {
                _token      : csrf_token,
                profits     : [],
                expenses    : [],
                blogCount,
                targetHistory,
                totalProfit
            };
            $(".profit-row").each(function(index, element){
                ajaxData.profits.push({
                    key     : $(element).attr('data-profit-key'),
                    name    : $(element).attr('data-profit-name'),
                    price   : parseFloat($(element).attr('data-profit-price')),
                });
            });
            $(".expense-row").each(function(index, element){
                ajaxData.expenses.push({
                    key     : $(element).attr('data-expense-key'),
                    name    : $(element).attr('data-expense-name'),
                    price   : parseFloat($(element).find('.expense-value-input').val()),
                });
            });

            $.ajax({
                type: "POST",
                url: siteUrl + "/track-profit-loss-history",
                data: ajaxData,
                success: function(){
                    $.notify('Profit and Loss Data saved successfully.', { type: 'success' });
                    location.reload();
                }
            });
        });
    },

    calculateProfit: function(){
        var totalExpense = 0;
        $(".expense-row").each( (index, expenseRow) => {

            let value = parseFloat($(expenseRow).find('.expense-value-input').val());
            if( $(expenseRow).attr("data-blog-row") == "true" ) {
                totalExpense += value * blogCount;
            }
            else
                totalExpense += value;
        });
        $("#total-expense-value").html("$" + totalExpense.toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }));
        $("#final-profit").html("$" + (totalProfit-totalExpense).toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }));
    }
};

$(document).ready(function(){
    Profit_Loss.init();
})
