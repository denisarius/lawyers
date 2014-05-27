$(document).ready(function () {
    $(".wrap_index_header_link").hover(function () {
        $(".index_header_inner").andSelf().css("background", "url(img/header_img_back.jpg)");
    });
    $(".wrap_index_header_link.right").hover(function () {
        $(".index_header_inner").andSelf().css("background", "url(img/header_img_back_right.jpg)");
    });


    $(".wrap_index_header_link.right").hover(
        function (event) {
            event.stopPropagation();
            $(".index_header_inner").css("background", "url(img/header_img_back_right.jpg)");
        }).mouseleave(function (event) {
            event.stopPropagation();
            $(".index_header_inner").css("background", "url(img/header_img_back_right.jpg)");
        }
    )

    //accordion
    $(".left_menu_accordion > li.drop > a, .left_menu_accordion li.drop div").on('click', function () {
        if ($(".submenu_accordion").is(':visible')) {
            var accord = $(this).siblings(".submenu_accordion");
            $(".left_menu_accordion > li.drop").not($(this).parent("li")).removeClass("opened");
            $(".submenu_accordion").not(accord).slideUp(800);
        }
        $(this).parent("li.drop").toggleClass("opened");
        $(this).siblings(".submenu_accordion").stop().animate({"height": "toggle"}, 800);
    });

    $(".left_menu_accordion > li.drop > a").click(function (event) {
        event.preventDefault();
    });

    $("table.prices_container tr:even").addClass("grey");
    $("table.prices_container tr:odd").addClass("white");

});