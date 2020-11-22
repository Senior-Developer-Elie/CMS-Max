
var Credit_Comparison = {

    init: function(){

        Credit_Comparison.initActions();
        //Calculate
        Credit_Comparison.calculateRatings();
    },

    initActions : function(){

        $(document).on("keyup", ".variable input", function(event){
            Credit_Comparison.calculateRatings();
        });

        $("#generate-pdf-button").click(function(){

                Credit_Comparison.getAllVariables();

                $("#defaultRate").val(JSON.stringify(cardRatings));
                $("#ccRepValue").val($("#cc-rep").is(":checked") ? 'on' : 'off');

                $("#ratingForm").submit();

                $("#generatePDFModal").modal('hide');
        });

        //CC Checkbox Action
        $("#cc-rep").change(function(){
            if( $(this).is(":checked") )
            {
                $("#commision-table-wrapper").show();
            }
            else
                $("#commision-table-wrapper").hide();
        });
    },

    getAllVariables: function(){
        $("td.variable").each(function(index, element){
            let value = Credit_Comparison.customParseFloat($(element).find("input").val());
            setValueByIndex($(element).attr('data-indexes'), value);
        });
    },

    calculateRatings: function(){

        Credit_Comparison.getAllVariables();
        var existingTotalFee = 0;
        var proposedTotalFee = 0;
        var totalCommision = 0;
        var monthlyFeeCommision = 0;

        let buyRate = Credit_Comparison.customParseFloat($(".variable.buy-rate input").val());
        let transBuyRate = Credit_Comparison.customParseFloat($(".variable.trans-buy-rate input").val());
        let makePercent = Credit_Comparison.customParseFloat($(".variable.make-percent input").val());
        let monthlyFeeRate = Credit_Comparison.customParseFloat($(".variable.monthly-fee-rate input").val());

        $("div.ratings-table-wrapper").each(function(index, ratingWrapper){

            var totalProcessingFee = 0;
            //Calculate Interchanges
            $(ratingWrapper).find(".interchanges-body").each(function(interChangeIndex, interchangeBody){

                var totalCost = 0;
                $(interchangeBody).find("tr.record-row").each(function(index, interchangeRecord){
                    let volume  = Credit_Comparison.customParseFloat($(interchangeRecord).find("td.volume input").val());
                    let rate    = Credit_Comparison.customParseFloat($(interchangeRecord).find("td.rate input").val());
                    let cost    = volume*rate/100;
                    totalCost   += cost;
                    $(interchangeRecord).find("td.cost").html("$" + cost.toFixed(2) );

                    //Calculate Commision Table
                    if( $(ratingWrapper).attr('id') == 'proposed-table' )
                    {
                        let commissionCost = (rate-buyRate)*volume/100;
                        $("tbody.buy-rate-body tr").eq(interChangeIndex).find('.cost').html( "$" + commissionCost.toFixed(2) );

                        totalCommision += commissionCost;
                    }
                });
                $(interchangeBody).find("td.total-cost").html("$" + totalCost.toFixed(2));

                totalProcessingFee += totalCost;
            });

            //Calculate Credit
            creditsBody = $(ratingWrapper).find(".credits-body");

            var totalCost = 0;
            creditsBody.find("tr.credit-row").each(function(creditIndex, creditRow){
                let auth_fee            = Credit_Comparison.customParseFloat($(creditRow).find('td.auth-fee input').val());
                let sharp_transaction   = Credit_Comparison.customParseFloat($(creditRow).find('td.sharp-transaction input').val());
                let cost                = auth_fee*sharp_transaction;
                totalCost               += cost;
                $(creditRow).find("td.cost").html("$" + cost.toFixed(2));

                //Calculate Commision Table
                if( $(ratingWrapper).attr('id') == 'proposed-table' )
                {
                    let commissionCost = (auth_fee-transBuyRate)*sharp_transaction;
                    $("tbody.trans-buy-rate-body tr").eq(creditIndex).find('.cost').html( "$" + commissionCost.toFixed(2) );
                    totalCommision += commissionCost;
                }
            })
            totalProcessingFee += totalCost;
            $(creditsBody).find(".total-cost").html("$" + totalCost.toFixed(2));

            //Caculate Pin Debits/AVS
            pinDevsBody = $(ratingWrapper).find(".pin-devs-body");
            var totalCost = 0;
            $(pinDevsBody).find("tr.credit-row").each(function(index, pinDevRow){
                let transaction_fee     = Credit_Comparison.customParseFloat($(pinDevRow).find('td.transaction-fee input').val());
                let sharp_transaction   = Credit_Comparison.customParseFloat($(pinDevRow).find('td.sharp-transaction input').val());
                let cost                = transaction_fee*sharp_transaction;
                totalCost               += cost;
                $(pinDevRow).find("td.cost").html("$" + cost.toFixed(2));
            });
            totalProcessingFee += totalCost;
            $(pinDevsBody).find(".total-cost").html("$" + totalCost.toFixed(2));

            feesBody = $(ratingWrapper).find(".fees-body");
            //Set Total Process Fee
            $(feesBody).find(".total-processing-fee-row td.cost").html("$" + totalProcessingFee.toFixed(2));

            //Calculate Total Fee
            var totalFee = totalProcessingFee;
            $(feesBody).find("tr.fee-row").each(function(index, feeRow){
                totalFee += Credit_Comparison.customParseFloat($(feeRow).find("td.fee input").val());
            });

            $(feesBody).find("td.total-cost").html("$" + totalFee.toFixed(2));

            if( $(ratingWrapper).attr('id') == 'existing-table' )
                existingTotalFee = totalFee;
            else
                proposedTotalFee = totalFee;
        });

        //Monthly Fee Commission
        let proposedFirstMonthlyServiceFee = getValueByIndex("1,fees,0,value,0");
        monthlyFeeCommision = proposedFirstMonthlyServiceFee - monthlyFeeRate;
        $("td.monthly-fee-commission").html("$" + monthlyFeeCommision.toFixed(2));

        //Estimated Commision
        let estimatedCommision = totalCommision * makePercent + monthlyFeeCommision;
        $(".estimated-commission").html("$" + estimatedCommision.toFixed(2));

        //Estimate Total Fee
        let monthlySavingCost       = existingTotalFee - proposedTotalFee;
        let monthlySavingPercent    = monthlySavingCost / existingTotalFee * 100;

        $("#total-result-table .estimated-monthly-saving-cost").html("$"+monthlySavingCost.toFixed(2));
        $("#total-result-table .estimated-monthly-saving-percent").html(monthlySavingPercent.toFixed(2)+"%");
        $("#total-result-table .estimated-annual-saving-cost").html("$"+(monthlySavingCost*12).toFixed(2));
        $("#total-result-table .estimated-three-annual-saving-cost").html("$"+(monthlySavingCost*36).toFixed(2));
    },
    customParseFloat : function(str){
        return parseFloat(str.trim().replace(',', '').replace(' ', ''));
    }
};
$(document).ready(function(){
    Credit_Comparison.init();
})

function setValueByIndex(indexStr, value)
{
    let indexes = indexStr.split(',');
    if( indexes.length == 2 )
        cardRatings[indexes[0]][indexes[1]] = value;
    if( indexes.length == 3 )
        cardRatings[indexes[0]][indexes[1]][indexes[2]] = value;
    if( indexes.length == 4 )
        cardRatings[indexes[0]][indexes[1]][indexes[2]][indexes[3]] = value;
    if( indexes.length == 5 )
        cardRatings[indexes[0]][indexes[1]][indexes[2]][indexes[3]][indexes[4]] = value;
    if( indexes.length == 6 )
        cardRatings[indexes[0]][indexes[1]][indexes[2]][indexes[3]][indexes[4]][indexes[5]] = value;
}

function getValueByIndex(indexStr)
{
    let indexes = indexStr.split(',');
    if( indexes.length == 2 )
        return cardRatings[indexes[0]][indexes[1]];
    if( indexes.length == 3 )
        return cardRatings[indexes[0]][indexes[1]][indexes[2]];
    if( indexes.length == 4 )
        return cardRatings[indexes[0]][indexes[1]][indexes[2]][indexes[3]];
    if( indexes.length == 5 )
        return cardRatings[indexes[0]][indexes[1]][indexes[2]][indexes[3]][indexes[4]];
    if( indexes.length == 6 )
        return cardRatings[indexes[0]][indexes[1]][indexes[2]][indexes[3]][indexes[4]][indexes[5]];
}
