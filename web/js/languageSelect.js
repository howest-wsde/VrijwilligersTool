$(function() {

    function load() {
        console.log("loaded languageselect");
        $(".dropdown-menu > li > a").click(function() {
            console.log("clicked")
        });
    }

    // Shady, I know... but how else am I going to guarantee the other lib is loaded?
    window.setTimeout(load, 3500);
});