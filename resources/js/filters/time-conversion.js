export default (value) => {
    return moment.unix(value).format('h:mm A');
}
