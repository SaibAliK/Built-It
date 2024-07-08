import trans from '../trans';

export default (value) => {
    value = new Date(value * 1000)
    var timeNow = new Date().getTime(),
        difference = timeNow - value.getTime(),
        seconds = Math.floor(difference / 1000),
        minutes = Math.floor(seconds / 60),
        hours = Math.floor(minutes / 60),
        days = Math.floor(hours / 24);
    if (days > 1) {
        return days + trans.methods.__(" days ago");
    } else if (days === 1) {
        return trans.methods.__("1 day ago");
    } else if (hours > 1) {
        return hours + trans.methods.__(" hours ago");
    } else if (hours === 1) {
        return trans.methods.__("an hour ago");
    } else if (minutes > 1) {
        return minutes + trans.methods.__(" min ago");
    } else if (minutes === 1) {
        return trans.methods.__("a min ago");
    } else {
        return trans.methods.__("a few sec ago");
    }
}
