export default (value) => {
    let star_rate = value;
    if (value > 0 && value < 0.5) {
        star_rate = 0.5;
    }
    if (value > 0.5 && value < 1) {
        star_rate = 1;
    }
    if (value > 1 && value < 1.5) {
        star_rate = 1.5;
    }
    if (value > 1.5 && value < 2) {
        star_rate = 2;
    }
    if (value > 2 && value < 2.5) {
        star_rate = 2.5;
    }
    if (value > 2.5 && value < 3) {
        star_rate = 3;
    }
    if (value > 3 && value < 3.5) {
        star_rate = 3.5;
    }
    if (value > 3.5 && value < 4) {
        star_rate = 4;
    }
    if (value > 4 && value < 4.5) {
        star_rate = 4.5;
    }
    if (value > 5) {
        star_rate = 5;
    }
    return star_rate;
}

