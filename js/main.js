
$.root_ = $("body");

$.fn.extend(
{
    jarvismenu: function (a) 
    {
        var b = 
        {
            accordion: "true",
            speed: 200,
            closedSign: "[+]",
            openedSign: "[-]"
        },
        c = $.extend(b, a),
        d = $(this);
        d.find("li").each(function () 
        {
            0 !== $(this).find("ul").size() && ($(this).find("a:first").append("<b class='collapse-sign'>" + c.closedSign + "</b>"), "#" == $(this).find("a:first").attr("href") && $(this).find("a:first").click(function () {
                return !1
            }))
        }), 
        d.find("li.active").each(function () 
        {
            $(this).parents("ul").slideDown(c.speed), $(this).parents("ul").parent("li").find("b:first").html(c.openedSign), $(this).parents("ul").parent("li").addClass("open")
        }), 
        d.find("li a").click(function () 
        {
            0 !== $(this).parent().find("ul").size() && (c.accordion && ($(this).parent().find("ul").is(":visible") || (parents = $(this).parent().parents("ul"), visible = d.find("ul:visible"), visible.each(function (a) {
                var b = !0;
                parents.each(function (c) 
                {
                    return parents[c] == visible[a] ? (b = !1, !1) : void 0
                }), b && $(this).parent().find("ul") != visible[a] && $(visible[a]).slideUp(c.speed, function () 
                {
                    $(this).parent("li").find("b:first").html(c.closedSign), $(this).parent("li").removeClass("open")
                })
            }))), $(this).parent().find("ul:first").is(":visible") && !$(this).parent().find("ul:first").hasClass("active") ? $(this).parent().find("ul:first").slideUp(c.speed, function () {
                $(this).parent("li").removeClass("open"), $(this).parent("li").find("b:first").delay(c.speed).html(c.closedSign)
            }) : $(this).parent().find("ul:first").slideDown(c.speed, function () {
                $(this).parent("li").addClass("open"), $(this).parent("li").find("b:first").delay(c.speed).html(c.openedSign)
            }))
        })
    }
});

function toggleFullScreen ()
{
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

function toggleMenu () 
{
    $("html").toggleClass("hidden-menu-mobile-lock");
    $.root_.toggleClass("hidden-menu");
    $.root_.removeClass("minified");
    adjustContentArea ();
}

function adjustContentArea ()
{
    var $window = $(window);
    var $content = $("#content");
    var contentOffset = $content.offset();
    $content.height($window.height() - contentOffset.top);
    $content.width($window.width() - contentOffset.left);
}

function loadUrl (url)
{
    $("#content").attr('src', url);
    $("#left-panel nav li.active").removeClass("active");
    $('#left-panel nav li:has(a[href="' + url + '"])').addClass("active");
}

function setupApplication ()
{
    $.root_.on("click", '[data-action="toggleFullscreen"]', function (event) { toggleFullScreen(); event.preventDefault(); });
    $.root_.on("click", '[data-action="toggleMenu"]', function (event) { toggleMenu(); event.preventDefault(); });
    $("#search-mobile").click(function () { $.root_.addClass("search-mobile") });
    $("#cancel-search-js").click(function () { $.root_.removeClass("search-mobile") });
    $("nav ul").jarvismenu(
    {
        accordion: true,
        speed: 2,
        closedSign: '<em class="fa fa-plus-square-o"></em>',
        openedSign: '<em class="fa fa-minus-square-o"></em>'
    });
    $(window).bind("load resize", function() { adjustContentArea(); });
    $(document).ready(function () { adjustContentArea(); });
    $('#left-panel nav a[href!="#"]').on ("click", function(event) 
    {
        loadUrl ($(this).attr("href"));
        event.preventDefault();
    });
}

setupApplication ();