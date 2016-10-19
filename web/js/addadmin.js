"use strict";

var arPeople = [];
var arTimerPeopleSearch = Array();
$(document).ready(function(){
    $("input[data-dropdown=person]").keyup(function(){
        clearTimeout(arTimerPeopleSearch["timer"]);
        var strID = $(this).attr("data-rel");
        if (typeof(strID) === typeof(undefined) || strID === false) {
            strID = "testettetet";
            $(this).attr("data-rel", strID);
        }
        $("#" + strID + " li").remove();
        $("#" + strID).append($("<li />").html("bezig met zoeken... "));
        arTimerPeopleSearch["id"] = strID;
        arTimerPeopleSearch["val"] = $(this).val();
        arTimerPeopleSearch["timer"] = setTimeout(function(){
            $.getJSON(RV_GLOBALS["usersearchURL"], {person: arTimerPeopleSearch["val"]}, function( data ) {
                arPeople = data;
                showPeople(arTimerPeopleSearch["id"]);
            });
        }, 500);
    });
})

function showPeople(strID){
    var oInput = $("input[data-rel=" + strID + "]");
    var strInput = oInput.val();
    if ($("#" + strID).length == 0) {
        oInput.after($("<ul />").attr("id", strID));
    }
    $("#" + strID + " li").remove();
    arPeople.forEach(function(person) {
        //if (person.name.toLowerCase().indexOf(strInput.toLowerCase()) >= 0) {
            $("#" + strID).append(
                $("<li />").append(
                    $("<a />")
                        .attr("href", "#")
                        .attr("data-id", person.id)
                        .attr("data-name", person.name)
                        .html(person.name)
                        .click(function(){
                            oInput.val("");
                            $("#" + strID + " li").remove();
                            var personname = $(this).attr("data-name");
                            var personid = $(this).attr("data-id");
                            $("ul#administrators").append(
                                $("<li />").append(
                                    $("<span />").html(personname)
                                ).append(
                                    $("<span />").html(" - ")
                                ).append(
                                    $("<input />").attr("type", "hidden").attr("name", "admin[]").val(personid)
                                ).append(
                                    $("<a />").attr("href", "#").html("ontneem admin rechten").click(function(){
                                            $(this).parentsUntil("ul").remove();
                                            return false;
                                    })
                                )
                            )
                            return false;
                        })
                )
            )
        //}
    });
    $
}