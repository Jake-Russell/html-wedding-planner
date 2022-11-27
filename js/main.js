$( document ).ready(function() {
    $("#submit").click(function (event) {
        event.preventDefault();
        $("#results").html("");

        var startDateInput = new Date($('#startDate').val());
        var lastDateInput = new Date($('#lastDate').val());
        var startDate = ('0' + startDateInput.getDate()).slice(-2) + '/' + ('0' + (startDateInput.getMonth()+1)).slice(-2) + '/' + startDateInput.getFullYear();
        var lastDate = ('0' + lastDateInput.getDate()).slice(-2) + '/' + ('0' + (lastDateInput.getMonth()+1)).slice(-2) + '/' + lastDateInput.getFullYear();
        var partySize = $("#partySize").val();
        var cateringGrade = $("#cateringGrade").val();
        var sortBy = $("#sortBy").val();

        $("#results").load("weddingData.php?startDate=" + startDate + "&lastDate=" + lastDate + "&partySize=" + partySize + "&cateringGrade=" + cateringGrade + "&sortBy=" + sortBy + "&filterByLicensed=0");
    });
});

// Called When Checkbox Is Clicked //
function checkboxStateChanged(){
    var checkbox = document.getElementById("filterByLicensed");
    var startDateInput = new Date($('#startDate').val());
    var lastDateInput = new Date($('#lastDate').val());
    var startDate = ('0' + startDateInput.getDate()).slice(-2) + '/' + ('0' + (startDateInput.getMonth()+1)).slice(-2) + '/' + startDateInput.getFullYear();
    var lastDate = ('0' + lastDateInput.getDate()).slice(-2) + '/' + ('0' + (lastDateInput.getMonth()+1)).slice(-2) + '/' + lastDateInput.getFullYear();
    var partySize = $("#partySize").val();
    var cateringGrade = $("#cateringGrade").val();
    var sortBy = $("#sortBy").val();

    // If Checkbox Is Checked, Load Page With &filterByLicensed=1
    if(checkbox.checked == true){
        $("#results").load("weddingData.php?startDate=" + startDate + "&lastDate=" + lastDate + "&partySize=" + partySize + "&cateringGrade=" + cateringGrade + "&sortBy=" + sortBy + "&filterByLicensed=1");
    }
    else{
        $("#results").load("weddingData.php?startDate=" + startDate + "&lastDate=" + lastDate + "&partySize=" + partySize + "&cateringGrade=" + cateringGrade + "&sortBy=" + sortBy + "&filterByLicensed=0");
    }
}

// Called To Alert Error Messages //
function errorMessage(error){
    switch(error){
        case(0):
            alert("Error. Invalid start date entered. It cannot be in the past.");
            break;
        case(1):
            alert("Error. The first available date must be before or the same as the last available date.");
            break;
        case(2):
            alert("Error. Invalid party size entered.");
            break;
        case(3):
            alert("Error. Invalid catering grade entered.");
            break;
        case(4):
            alert("There are no available venues matching the given criteria.");
            break;
    }
}