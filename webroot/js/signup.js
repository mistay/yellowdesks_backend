$( document ).ready(function() {

    // show labels as soon user provided values
    $("input[type=text], input[type=password], input[type=checkbox]").bind("input change", function() {
        if (revalidate)
            formvalidataion();
        $(this).val() == "" ? $("label[for='" + this.name + "']").fadeOut() : $("label[for='" + this.name + "']").fadeIn();
    });

    // hide labels after startup
    $("input[type=text], input[type=password]").each(function() {
        $("label[for='" + this.name + "']").hide();
    });

    $("#companyname").bind("input", function(){
        $(this).val() == "" ? $(".check.companyname").fadeIn() : $(".check.companyname").fadeOut();
    });

    $("#password").bind("input", function() {
        $(this).val().length < 8 ? $(".check.password").fadeIn() : $(".check.password").fadeOut();
    });

    $("#step2").hide();

    loaded=false;

    $("#next").click(function() {
        revalidate=true;
        if (!formvalidataion()) {
            revalidate = false;
            $("#step1").fadeOut();
            $("#step2").delay(500).fadeIn(400, function() {
                    // load google map only if #map is visible
                    //<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap"></script>
                    if(!loaded)
                        $.getScript( "https://maps.googleapis.com/maps/api/js?key=AIzaSyD4HecLgzMZ6sK8fYSracEULluXdujR8BU&callback=initMap");
                    loaded=true;
                });
        }
        // switch to next but do _not_ submit form
        return false;
    });

    $("#previous").click(function() {
        $("#step2").fadeOut();
        $("#step1").delay(500).fadeIn();
        return false;
    });

    $("#finish").click(function() {
        if (!formvalidataion()) {

        } else {
            // do not submit form
            return false;
        }
    });
});

var revalidate=false;
function formvalidataion() {
    errors = false;
    $("input:visible").each(function() {
        if ($(this).val() == "") {
            $(this).addClass("error");
            errors = true;
        }
        else
            $(this).removeClass("error");
    });

    $("input[type=checkbox]").each(function() {
        if ($(this).is(':not(:checked)')) {
            errors = true;
            $("label[for="+this.name+"]").addClass("error");
            $(this).addClass("error");
        } else {
            $("label[for="+this.name+"]").removeClass("error");
            $(this).removeClass("error");
        }
    });

    return errors;
}