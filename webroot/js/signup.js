$( document ).ready(function() {

    // show labels as soon user provided values
    $("input[type=text]").keyup(function(){
        $(this).val() == "" ? $("label[for='" + this.name + "']").fadeOut() : $("label[for='" + this.name + "']").fadeIn();
    });

    // hide labels after startup
    $("input[type=text]").each(function() {
        $("label[for='" + this.name + "']").hide();
    });

    $("#companyname").keyup(function(){
        $(this).val() == "" ? $(".check.companyname").fadeIn() : $(".check.companyname").fadeOut();
    });

    $("#password").keyup(function(){
        console.log($(this).val().length);
        $(this).val().length < 8 ? $(".check.password").fadeIn() : $(".check.password").fadeOut();
    });
});