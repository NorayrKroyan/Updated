$(document).ready(function(){  
    $(".image").on('click', function(e){
        e.preventDefault(); 
        var id = $(this).data("id");
        var token = $('input[name="_token"]').val();
        console.log(token)
        $.ajax(
            {
                type: "delete",
                url: "http://laravelauth.loc/image/delete/"+id,
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data){
                    if(data.status === 'deleted' ) {
                        $('.delete-class-' + id).remove();
                    }
                }
            });

            console.log(5555,id)
        });













});  