function smoothScroll(el, to, duration) { // this works with zepto
    if (duration < 0) { return; }
    var difference = to - $(window).scrollTop();
    var perTick = difference / duration * 10;
    this.scrollToTimerCache = setTimeout(function() {
        if (!isNaN(parseInt(perTick, 10))) {
            window.scrollTo(0, $(window).scrollTop() + perTick);
            smoothScroll(el, to, duration - 10);
        }
    }.bind(this), 10);
}

$(document).ready(function(){

    //console.log($('html,body').scrollTop());

    $("#main-navigation a").each(function(index) {        
        if(this.href.trim() === window.location.href) {
            $(this).closest("li").addClass("active");
        }
    });

    $(".side-menu a").on('click', function(e) {
        e.preventDefault();
        smoothScroll($(window), $($(e.currentTarget).attr("href")).offset().top, 200);
    });    

    $(".ellipsis-control").on("click", function(event){
        event.preventDefault();
        $(this).closest("article").find(".ellipsis").toggle();
        $(this).find(".open, .close").toggle();
    });

    $("#listen-live, #listen-live-small").click(function(e) {
        e.preventDefault();
        window.open($(this).attr("href"), "popupTunerWindow", "").focus();
        //width=800,height=240,scrollbars=no
    });

});