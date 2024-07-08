module.exports = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace) {
            let translationNotFound = true
            let keyLowerCase = key.toLowerCase()
            // let translations = window.Laravel.translations[window.Laravel.locale]
            let translations = window.translate[window.Laravel.locale]
            let translatedKey = translations[keyLowerCase];
            if(translatedKey){
                translationNotFound = false
            }

            if (translationNotFound){
                return key;
            }
            return translatedKey;
        }
    },
}
