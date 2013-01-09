
$(document).ready(function() {
    redrawSelectBoxes();
});
function redrawSelectBoxes() {
    $(".ddn-container, .ddn").remove();
    var $selectBoxes = $(".selectlist");
    for (var i = 0; i < $selectBoxes.length; i++) {
        var ddl = $selectBoxes.get(i);
        var width = ddl.style.width ? ddl.style.width : "175px";
        var selText = ddl.selectedIndex != null ? ddl.options[ddl.selectedIndex].text : "";
        var div = "<div class='ddn ddn-selector' style='width: " + width + "' parent='" + ddl.id + "' id='div_" + ddl.id +
                          "'>" + selText + "</div><div id='div_" + ddl.id + "_Container' class='ddn-container'>";
        for (var o = 0; ddl.options[o]; o++) {
            div += "<div index='" + o + "' class='ddn-item' parent='" + ddl.id + "'>" + ddl.options[o].text + "</div>";
        }
        div += "</div>";
        $("#" + ddl.id).after(div);
        $("#" + ddl.id).css("display", "none");
    }
    $(".ddn-container").hide();
    $(".ddn-selector").click(function(e) {
        var id = $(this).attr("id");
        var $container = $("#" + id + "_Container")
        if (!$container.css("display") || $container.css("display") == "none")
            $container.slideDown();
        else
            $container.slideUp();
        e.preventDefault();
    });
    $(".ddn-item").click(function() {
        var $this = $(this);
        var $parentId = $this.attr("parent")
        var $parent = $("#" + $parentId)
        $parent[0].selectedIndex = $(this).attr("index");
        $("#div_" + $parentId).text($(this).text());
        $("#div_" + $parentId).trigger('click');
        if ($parent[0].attributes["onchange"])
            eval($parent[0].attributes["onchange"].value);
    });
    $(".ddn-item").hover(function() {
        $(this).toggleClass("ddn-item-hover");
    },
    function() {
        $(this).toggleClass("ddn-item-hover");
    });
}
