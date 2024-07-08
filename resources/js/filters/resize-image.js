export default (source, width, height) => {
    if (source != null) {
        var basePath = window.Laravel.base;
        if (source.includes(basePath)) {
            source = source.replace(basePath, '');
        }
    }

    let url = `${window.Laravel.base}/images/timthumb.php?src=${source + ((width) ? '&w=' + width : '') + ((height) ? '&h=' + height : '')}&zc=1&q=90&s=0`;
    return url;
}
