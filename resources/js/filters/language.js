export default (value) => {
     if (window.Laravel.locale === 'ar' && value['ar'] === ''){
         return value['en']
     }
    return value[window.Laravel.locale];
}

