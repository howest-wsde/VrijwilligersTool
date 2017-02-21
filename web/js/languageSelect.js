function addLanguageSelectListeners() {

    var languages = ["nl", "en"];
    var semanticLanguages =
        {"nl": "Nederlands",
         "en": "English"};

    var currentSemanticLanguage = semanticLanguages[getLanguageFromUrl()[0]];
    console.log(currentSemanticLanguage);
    $('.selectpicker').selectpicker('val', currentSemanticLanguage);

    $('#language-select').on('changed.bs.select', function (e, clickedIndex) {

        var oldLanguage = getLanguageFromUrl()[0].replace("/","");
        var newLanguage = languages[clickedIndex];

        if (oldLanguage != newLanguage) {
            // exception for nl.... I know it's weird
            if (newLanguage == languages[0]) {
                newLanguage = "";
            } else {
                newLanguage = "\/" + newLanguage;
            }

            var newUrl = newLanguage + stripLanguageFromUrl();
            browseTo(newUrl);
        }
    });

    function getLanguageFromUrl() {
        var url = window.location.pathname;

        for (var i = 0; i < languages.length; i++ ) {
            var language = languages[i];
            if (url.indexOf(`/${languages[i]}/`) != -1 ) {
                return [language, true];
            }
        }

        return [languages[0], false]; // default nl when no locale in url;
    }

    function stripLanguageFromUrl() {
        var [language, languageInUrl] = getLanguageFromUrl();

        var url = window.location.pathname;

        if (languageInUrl) {
            return url.substring(language.length + 1);
        }
        else {
            return url;
        }
    }

    function browseTo(newUrl) {
        window.location.href = newUrl;
    }
}