
  // $(function() {
  //   var body = $('body');
  //   var contentWrapper = $('.content-wrapper');
  //   var scroller = $('.container-scroller');
  //   var footer = $('.footer');
  //   var sidebar = $('.sidebar');

  //   sidebar.on('show.bs.collapse', '.collapse', function() {
  //     sidebar.find('.collapse.show').collapse('hide');
  //   });

  //   $('[data-toggle="minimize"]').on("click", function() {
  //     if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
  //       body.toggleClass('sidebar-hidden');
  //     } else {
  //       body.toggleClass('sidebar-icon-only');
  //     }
  //   });
  // });




  // $(document).on('mouseenter mouseleave', '.sidebar .nav-item', function(ev) {
  //   var body = $('body');
  //   var sidebarIconOnly = body.hasClass("sidebar-icon-only");
  //   var sidebarFixed = body.hasClass("sidebar-fixed");
  //   if (!('ontouchstart' in document.documentElement)) {
  //     if (sidebarIconOnly) {
  //       if (sidebarFixed) {
  //         if (ev.type === 'mouseenter') {
  //           body.removeClass('sidebar-icon-only');
  //         }
  //       } else {
  //         var $menuItem = $(this);
  //         if (ev.type === 'mouseenter') {
  //           $menuItem.addClass('hover-open')
  //         } else {
  //           $menuItem.removeClass('hover-open')
  //         }
  //       }
  //     }
  //   }
  // });


  // $(function() {
  //   $('[data-toggle="offcanvas"]').on("click", function() {
  //     $('.sidebar-offcanvas').toggleClass('active')
  //   });
  // });

$( document ).ready(function() {
    $(".sidebar-dropdown > a").click(function() {
    $(".sidebar-submenu").slideUp(200);
      if (
      $(this)
        .parent()
        .hasClass("active")
    ) {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });

  $(".toggle-sidebar").click(function() {
    $(".main-site").toggleClass("toggled");
  });
  $("#show-sidebar").click(function() {
   $(".main-site").addClass("toggled");
  });
     
});


$(document).ready(function(){
  $('#reviewrating').owlCarousel({
  loop:true,
  margin:10,
  nav:true,
  responsive:{
      0:{
          items:1
      },
      600:{
          items:2
      },
      1000:{
          items:3
      }
  }
  })
});


$(document).ready(function(){
  $('.communitycarousel').owlCarousel({
  loop:false,
  margin:10,
  nav:false,
  dots:false,
  responsive:{
      0:{
          items:1
      },
      600:{
          items:3
      },
      1000:{
          items:5
      }
  }
  })
});


$(document).ready(function(){
  $('.communitycarousel1').owlCarousel({
  loop:false,
  margin:10,
  nav:false,
  dots:false,
  responsive:{
      0:{
          items:1
      },
      600:{
          items:1
      },
      1000:{
          items:1
      }
  }
  })
});

