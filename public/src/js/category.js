/**
 * Created by ilija on 4/29/17.
 */

function resetId(){

    $("#category_id").val("-1");
}

function updateCategory(category_id){

    var name = $("#cat"+category_id).find("td:nth-child(1)").text();
    var description = $("#cat"+category_id).find("td:nth-child(2)").text();

    $("#edit-modal").modal("show");
    $("#category_id").val(category_id);
    $("#category_name").val(name);
    $("#description").val(description);
}

function deleteCategory(category_id){


}