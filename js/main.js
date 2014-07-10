
function toggleFullScreen ()
{
    $.root_ = $("body");
    if ($.root_.hasClass("full-screen"))
    {
        $.root_.removeClass("full-screen");
        document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen && document.webkitExitFullscreen()
    }
    else
    {   
        var element = document.documentElement;
        $.root_.addClass("full-screen");
        element.requestFullscreen ? element.requestFullscreen() : element.mozRequestFullScreen ? element.mozRequestFullScreen() : element.webkitRequestFullscreen ? element.webkitRequestFullscreen() : element.msRequestFullscreen && element.msRequestFullscreen();
    }
}

function toggleSidebar () 
{
    $("body").toggleClass("collapsed");
}

function loadUrl (url)
{
    $("#iframe").attr("src", url);
    $("#sidebar ul li.active").removeClass("active");
    $('#sidebar ul li:has(a[href="' + url + '"])').addClass("active");
}

$(document).ready(function () 
{
    $('#sidebar ul li a[href!="#"]').on ("click", function(event) 
    {
        loadUrl ($(this).attr("href"));
        event.preventDefault();
    });
});