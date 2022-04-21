$(document).ready(function(){  
$(".delete-confirm").on('click', function(){
    // e.preventDefault(); 
    
    
    if (confirm("Do you want to delete whole information?")) {
        var id = $('.delete-confirm').data("id");
        var token = $('input[name="_token"]').val();
        
        $.ajax({
                type: "DELETE",
                url: "users/"+id,
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data){
                    if(data.status === 'deleted' ) {
                        $('.table-row-' + id).remove();
                    }
                }
            });
    }
    
});



});

