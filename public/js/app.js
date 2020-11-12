$(document).ready(function(){
    function DeleteNames(id){
        $.ajax('/names/delete/'+id, {
            type: 'delete',
            ContentType:"application/json",
            // data: { myData: 'This is my data.' },
            success: function (data, status, xhr) {
                alert(data.message)
                $("."+id).remove();
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert(jqXhr.statusText)
            }
        });
    }

    function GetNames(){
        $("#names tbody tr").remove();
        $.ajax('/names', {
            type: 'POST',
            ContentType:"application/json",
            // data: { myData: 'This is my data.' },
            success: function (data, status, xhr) {
                data.forEach(function(val, index) {
                    name = `
                     <tr class="${val.id}">
                        <th scope="row">${val.id}</th>
                        <td>${val.name}</td>
                        <td>
                            <button type="button" class="btn btn-danger deleteName" id="${val.id}">Delete</button>
                        </td>
                     </tr>
                    `;
                    $("#names tbody").append(name);
                });
                $(".deleteName").click(function(){
                    id = $(this).attr("id");
                    DeleteNames(id)
                });
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert(jqXhr.statusText)
            }
        });
    }
    GetNames()
    $("#update").click(function(){
        GetNames()
    });

    $("#name_form_submit").click(function(e){

        e.preventDefault();
        $.ajax('names/create', {
            type: 'post',
            data:  getFormData($("#name_form")),
            dataType: 'json',
            contentType: 'application/json',
            success: function (data, status, xhr) {
                alert(data.message)
                GetNames()
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert(jqXhr.statusText)
            }
        });


    });

    function getFormData(form){
        var unindexed_array = form.serializeArray();
        var indexed_array = {};
        $.map(unindexed_array, function(n, i){
            if(n['value']){
                indexed_array[n['name']] = n['value'];
            }
        });
        return JSON.stringify(indexed_array);
    }


});