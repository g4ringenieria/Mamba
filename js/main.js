
function loadUrl (url)
{
    $("#iframe").attr("src", url);
    $("#header ul li.active").removeClass("active");
    $('#header ul li:has(a[href="' + url + '"])').addClass("active");
}

$(document).ready(function () 
{
    $('#header ul li a[href!="#"]').on ("click", function(event) 
    {
        loadUrl ($(this).attr("href"));
        event.preventDefault();
    });
});