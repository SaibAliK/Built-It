export default (value) => {
    return moment().day(value+1).format('dddd') ;
}
