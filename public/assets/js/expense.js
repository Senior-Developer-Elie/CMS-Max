var Manage_Expense = {

    descriptionCKEditor : false,

    init: function(){
        this.initComponents();
        this.initAddEditActions();
    },

    initComponents: function(){
        //Create CK Editor
        ClassicEditor
        .create( $("#expense-description")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            Manage_Expense.descriptionCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initAddEditActions: function(){

        //Add Expense Button Click
        $("#add-expense-button").click(function(){
            Manage_Expense_Modal.showModal(-1);
        });

        //Edit Expense Button Click
        $(".edit-button").click(function(){
            expenseId = $(this).closest('tr').attr('data-expense-id');
            Manage_Expense_Modal.showModal(expenseId);
        });

        //Delete Expense Button Click
        $(".delete-button").click(function(){
            $("#delete-expense-modal").attr("data-expense-id", $(this).closest('tr').attr('data-expense-id'));
            $("#delete-expense-modal").modal('show');

        });

        //Confirm Expense Delete Button
        $("#delete-expense-modal .confirm-btn").click(function(){
            expenseId = $("#delete-expense-modal").attr("data-expense-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-expense',
                data: {
                    _token: csrf_token,
                    expenseId
                },
                success: function(data){
                    if( data.status == 'success' )
                    {
                        location.reload();
                    }
                }
            });
        });
    }
};

var Manage_Expense_Modal = {
    selectedExpenseId : -1,
    init: function(){
        this.initActions();
    },

    initActions: function(){

        //confirm save action
        $("#add-expense-modal .confirm-btn").click(function(){
            if( $(this).hasClass('disabled') )
                return;
            if( !Manage_Expense_Modal.validateForm() )
                return;

            $("#add-expense-modal .confirm-btn").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-edit-expense',
                data: {
                    _token: csrf_token,
                    expenseId: Manage_Expense_Modal.selectedExpenseId,
                    name: $("#expense-name").val(),
                    price: $("#expense-price").val(),
                    description: Manage_Expense.descriptionCKEditor.getData()
                },
                success: function(data){
                    if( data.status == 'success' )
                    {
                        location.reload();
                    }
                }
            });
        });
    },

    showModal: function(expenseId){
        Manage_Expense_Modal.selectedExpenseId = expenseId;

        if(Manage_Expense_Modal.selectedExpenseId == -1) {
            $("#expense-name").val('');
            $("#expense-price").val('');
            Manage_Expense.descriptionCKEditor.setData("");
            $("#add-expense-modal .modal-title").html("Add Expense");
            $("#add-expense-modal").modal('show');
        }
        else{
            //Get Expense Data
            $.ajax({
                type : "GET",
                url : siteUrl + "/get-expense-data",
                data: {
                    _token: csrf_token,
                    expenseId : Manage_Expense_Modal.selectedExpenseId,
                },
                success: function(data){
                    if(data.status == 'success'){
                        let expense = data.expense;
                        $("#expense-name").val(expense.name);
                        $("#expense-price").val(expense.price);
                        Manage_Expense.descriptionCKEditor.setData(expense.description == null ? "" : expense.description);
                        $("#add-expense-modal .modal-title").html("Edit Expense");
                        $("#add-expense-modal").modal('show');
                    }
                }
            })
        }
    },

    validateForm: function()
    {
        //name
        if( $("#expense-name").val() == "" )
        {
            $("#expense-name").focus();
            return false;
        }

        if( $("#expense-price").val() == "" )
        {
            $("#expense-price").focus();
            return false;
        }
        return true;
    }
};

$(document).ready(function(){
    Manage_Expense.init();
    Manage_Expense_Modal.init();
});
