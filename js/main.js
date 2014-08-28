
function loadUrl (url)
{
    $("#iframe").attr("src", url);
    $("#header li.active").removeClass("active");
    $('#header li:has(a[href="' + url + '"])').addClass("active");
}

$(document).ready(function () 
{
    $('#header a[href!="#"]').on ("click", function(event) 
    {
        loadUrl ($(this).attr("href"));
        event.preventDefault();
    });
});