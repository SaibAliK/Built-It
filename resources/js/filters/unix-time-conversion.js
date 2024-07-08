export default (value,format) => {
    if (value > 0 && value !=''){
        return moment.unix(value).format(format);
    }
    return "";
}
