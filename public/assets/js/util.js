/**
 * copyright cib 2015
 */
$(function () {
    var $menuselected = $("#topmenu a.selected");
	//var $homeselected = $("#topmenu a.home-selected").length>0;
	var $currentMenu=$("#topmenu a.selected");

    $("#topmenu>li").mouseenter(function (e) {
		//$homeselected?$currentMenu.removeClass("home-selected"):$currentMenu.removeClass("selected");
        //$currentMenu.removeClass("selected");
        var $submenu = $(this).children(".sub-menu");
        if ($submenu.length == 0) {			
            return false;
        }
        $submenu.show().find(".third-menu").show();
        $submenu.prev("a").addClass("selected");
        e.stopPropagation();
    }).mouseleave(function () {			
            var $submenu = $(this).children(".sub-menu");
            if ($submenu.length == 0) {
                return;
            }
            $submenu.hide(10).find(".third-menu").hide();
            $submenu.prev("a").removeClass("selected");
            
        });
	
});
