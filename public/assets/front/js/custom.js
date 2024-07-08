$('.slider-card-banner').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  infinite: true,
  autoplay: false,
  speed: 900,
  centerMode: false,
  arrow:true,
  prevArrow: $('.arrow-left-icon'),
  nextArrow: $('.arrow-right-icon'),
 
  responsive: [
    {
      breakpoint: 1450,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 1250,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 3,
        infinite: true,
      }
    },
    {
      breakpoint: 900,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 700,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }

  ]

});





$('.near-slider').slick({
  slidesToShow: 6,
  slidesToScroll: 1,
  infinite: true,
  autoplay: false,
  speed: 900,
  centerMode: false,
  arrow:false,

 
  responsive: [
    {
      breakpoint: 1450,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
      }
    },
    {
      breakpoint: 1250,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
      }
    },
    {
      breakpoint: 900,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }

  ]

});







  
  $('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav'
  });
  $('.slider-nav').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    dots: false,
    centerMode: true,
    centerPadding: '0px',
    focusOnSelect: true,
    prevArrow: $('.prev-d'),
    nextArrow: $('.next-d'),
  });
    

  


  
  

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
});


// car wash carousel




function openNav() {
  document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

$(document).ready(function() {
  $('.js-example-basic-multiple').select2();
  
});


$("body").on("click", ".xyz", function() {
  $(this)
    .prev()
    .prop("checked", true);
  if (that.form) {
    that.form.form.patchValue({
      rating: $(this)
        .prev()
        .val()
    });
  }
});




(function () {
  const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

  //I'm adding this section so I don't have to keep updating this pen every year :-)
  //remove this if you don't need it
  let today = new Date(),
      dd = String(today.getDate()).padStart(2, "0"),
      mm = String(today.getMonth() + 1).padStart(2, "0"),
      yyyy = today.getFullYear(),
      nextYear = yyyy + 1,
      dayMonth = "09/30/",
      birthday = dayMonth + yyyy;
  
  today = mm + "/" + dd + "/" + yyyy;
  if (today > birthday) {
    birthday = dayMonth + nextYear;
  }
  //end
  
  const countDown = new Date(birthday).getTime(),
      x = setInterval(function() {    

        const now = new Date().getTime(),
              distance = countDown - now;

        // document.getElementById("days").innerText = Math.floor(distance / (day)),
        //   document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
        //   document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
        //   document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

        //do something later when date is reached
        if (distance < 0) {
          document.getElementById("headline").innerText = "It's my birthday!";
          document.getElementById("countdown").style.display = "none";
          document.getElementById("content").style.display = "block";
          clearInterval(x);
        }
        //seconds
      }, 0)
  }());



  $(document).ready(function(){
    $(".near-card-main-p").click(function(){
      $(".card-click-show-main").addClass("intro-show");
    });
  });


