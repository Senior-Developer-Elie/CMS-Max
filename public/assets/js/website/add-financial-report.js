var Add_Financial_Report = {

    init : function(){
        this.initDatePicker();
        this.initInlineEdit();
        this.refreshStates();
    },

    initDatePicker() {
        $('input[name=date]').datepicker({
            autoclose: true,
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months",
            orientation: "bottom"
        })
    },

    initInlineEdit() {
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

            Add_Financial_Report.refreshStates();
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
    },

    refreshStates() {
        let totalProfit = 0
        $(".profit-value-input").each(function(index, element) {
            let price = parseFloat($(element).val());
            totalProfit += price > 0 ? price : 0;
        })

        let totalExpense = 0;
        $(".expense-value-input").each(function(index, element) {
            let price = parseFloat($(element).val());
            totalExpense += price > 0 ? price : 0;
        })

        $("#total-profit-value").text("$" + totalProfit.toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }));
        $("#total-expense-value").text("$" + totalExpense.toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }));
        $("#total-value").text("$" + (totalProfit - totalExpense).toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }));
        $("#expense-percentage-value").text((totalExpense * 100 / totalProfit).toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }) + "%");
    }
};

$(document).ready(function(){
    Add_Financial_Report.init();
})
