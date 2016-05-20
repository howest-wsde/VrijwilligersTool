$(function(){
    style.init();
});

var style = {

    init : function(){
        style.loadFormStyle();
        style.loadTitleStyle();
        style.loadShareButtonsStyle();
        style.loadPopupStyle();
    },

    loadFormStyle : function(){
        var m = style.main;
        m(style.formInputs)
            .addClass('form-control')
            .css("width","auto");
        m('textarea').css("resize","none");
        m(style.formInputs + ",label").css("margin","5px 0 5px 0");
        m('button,input[type="submit"]').addClass('btn btn-default');
    },

    loadTitleStyle : function(){
        var m = style.main;
        m('h1').css("font-size","2rem").css("margin-top","10px");
        m('h2').css("font-size","1.75rem").css("margin-top","7.5px");
        m('h3').css("font-size","1.5rem").css("margin-top","5px");
        m('h4').css("font-size","1.25rem").css("margin-top","2.5px");
    },
    
    loadShareButtonsStyle : function(){
        $(".share-buttons > div").css({
            "display":"block",
            "margin":"5px 0 5px 0"
        });
    },

    loadPopupStyle : function(){
        $(".black_overlay").css({
            "display": "none",
            "position": "fixed",
            "top": "0%",
            "left": "0%",
            "width": "100%",
            "height": "100vh",
            "background-color": "black",
            "z-index": "1001",
            "-moz-opacity": "0.8",
            "opacity":".80",
            "filter": "alpha(opacity=80)"
        });

        $(".white_content").css({
            "display": "none",
            "position": "fixed",
            "top": "25%",
            "left": "40%",
            "width": "20%",
            "height": "50vh",
            "padding": "1vh",
            "border": "5vh solid #efefef",
            "background-color": "white",
            "z-index":"1002",
            "overflow": "auto",
        });
    },

    formInputs : 'textarea,input[type="text"],input[type="tel"],input[type="email"],input[type="number"],input[type="password"],input[type="file"],input[type="date"],select',

    main : function(para){
        return $('main').find(para);
    }
};