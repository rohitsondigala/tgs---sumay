/* ====== Index ======

1. JEKYLL INSTANT SEARCH
2. SCROLLBAR CONTENT
3. TOOLTIPS AND POPOVER
4. JVECTORMAP DASHBOARD
5. JVECTORMAP ANALYTICS
6. JVECTORMAP WIDGET
7. MULTIPLE SELECT
8. LOADING BUTTON
  8.1. BIND NORMAL BUTTONS
  8.2. BIND PROGRESS BUTTONS AND SIMULATE LOADING PROGRESS
9. TOASTER
10. PROGRESS BAR

====== End ======*/

$(document).ready(function() {
  "use strict";

  /*======== 1. JEKYLL INSTANT SEARCH ========*/

  SimpleJekyllSearch.init({
    searchInput: document.getElementById('search-input'),
    resultsContainer: document.getElementById('search-results'),
    dataSource: '/assets/data/search.json',
    searchResultTemplate: '<li><div class="link"><a href="{link}">{label}</a></div><div class="location">{location}</div><\/li>',
    noResultsText: '<li>No results found</li>',
    limit: 10,
    fuzzy: true,
  });


  /*======== 2. SCROLLBAR CONTENT ========*/

  function scrollWithBigMedia(media) {
    var $elDataScrollHeight = $("[data-scroll-height]");
    if (media.matches) {
      /* The viewport is greater than, or equal to media screen size */
      $elDataScrollHeight.each(function() {
        var scrollHeight = $(this).attr("data-scroll-height");
        $(this).css({ height: scrollHeight + "px", overflow: "hidden" });
      });

      //For content that needs scroll
      $(".slim-scroll")
        .slimScroll({
          opacity: 0,
          height: "100%",
          color: "#999",
          size: "5px",
          wheelStep: 10
        })
        .mouseover(function() {
          $(this)
            .next(".slimScrollBar")
            .css("opacity", 0.4);
        });
    } else {
      /* The viewport is less than media screen size */
      $elDataScrollHeight.css({ height: "auto", overflow: "auto" });
    }
  }

  var media = window.matchMedia("(min-width: 992px)");
  scrollWithBigMedia(media); // Call listener function at run time
  media.addListener(scrollWithBigMedia); // Attach listener function on state changes

  /*======== 3. TOOLTIPS AND POPOVER ========*/
  $('[data-toggle="tooltip"]').tooltip({
    container: "body",
    template:
      '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
  });
  $('[data-toggle="popover"]').popover();

  /*======== 4. JVECTORMAP DASHBOARD ========*/
  var mapData = {
    US: 1298,
    FR: 540,
    DE: 350,
    BW: 450,
    NA: 250,
    ZW: 300,
    AU: 760,
    GB: 120,
    ZA: 450
  };

  if (document.getElementById("world")) {
    $("#world").vectorMap({
      map: "world_mill",
      backgroundColor: "transparent",
      zoomOnScroll: false,
      regionStyle: {
        initial: {
          fill: "#e4e4e4",
          "fill-opacity": 0.9,
          stroke: "none",
          "stroke-width": 0,
          "stroke-opacity": 0
        }
      },
      markerStyle: {
        initial: {
          stroke: "transparent"
        },
        hover: {
          stroke: "rgba(112, 112, 112, 0.30)"
        }
      },

      markers: [
        {
          latLng: [54.673629, -62.347026],
          name: "America",
          style: {
            fill: "limegreen"
          }
        },
        {
          latLng: [62.466943, 11.797592],
          name: "Europe",
          style: {
            fill: "orange"
          }
        },
        {
          latLng: [23.956725, -8.768815],
          name: "Africa",
          style: {
            fill: "red"
          }
        },
        {
          latLng: [-21.943369, 123.102198],
          name: "Australia",
          style: {
            fill: "royalblue"
          }
        }
      ]
    });
  }

  /*======== 5. JVECTORMAP ANALYTICS ========*/
  var mapData2 = {
    IN: 19000,
    US: 13000,
    TR: 9500,
    DO: 7500,
    PL: 4600,
    UK: 4000
  };

  if (document.getElementById("analytic-world")) {
    $("#analytic-world").vectorMap({
      map: "world_mill",
      backgroundColor: "transparent",
      zoomOnScroll: false,
      regionStyle: {
        initial: {
          fill: "#e4e4e4",
          "fill-opacity": 0.9,
          stroke: "none",
          "stroke-width": 0,
          "stroke-opacity": 0
        }
      },

      series: {
        regions: [
          {
            values: mapData2,
            scale: ["#6a9ef9", "#b6d0ff"],
            normalizeFunction: "polynomial"
          }
        ]
      }
    });
  }

  /*======== 6. JVECTORMAP WIDGET ========*/
  if (document.getElementById("demoworld")) {
    $("#demoworld").vectorMap({
      map: "world_mill",
      backgroundColor: "transparent",
      regionStyle: {
        initial: {
          fill: "#9c9c9c"
        }
      }
    });
  }

  /*======== 7. MULTIPLE SELECT ========*/
  $(".js-example-basic-multiple").select2();

  /*======== 8. LOADING BUTTON ========*/
  /* 8.1. BIND NORMAL BUTTONS */
  Ladda.bind(".ladda-button", {
    timeout: 5000
  });

  /* 7.2. BIND PROGRESS BUTTONS AND SIMULATE LOADING PROGRESS */
  Ladda.bind(".progress-demo button", {
    callback: function(instance) {
      var progress = 0;
      var interval = setInterval(function() {
        progress = Math.min(progress + Math.random() * 0.1, 1);
        instance.setProgress(progress);

        if (progress === 1) {
          instance.stop();
          clearInterval(interval);
        }
      }, 200);
    }
  });

  /*======== 9. TOASTER ========*/
  function callToaster(positionClass) {
    if (document.getElementById("toaster")) {
      toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: positionClass,
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
      };
      toastr.success("Welcome to sleek", "Howdy!");
    }
  }

  if (document.dir != "rtl" ){
    callToaster("toast-top-right");
  }else {
    callToaster("toast-top-left");
  }

  /*======== 10. PROGRESS BAR ========*/
  NProgress.done();


});
$(function() {
    $('.delete-item').click(function(e) {
        e.preventDefault();
        if (window.confirm("Are you sure?")) {
            let data_delete = $(this).attr('data-delete');
            $('.'+data_delete).submit();
        }
    });


    $('.search-data').on('keyup',function (){
        let data_search_url = $(this).attr('data-search-url');
        let data_route_url = $(this).attr('data-route-url');
        $.ajax({
            type: "GET",
            url: data_search_url+'?words='+$(this).val()+'&route='+data_route_url,
            success: function (res) {
                if (res) {
                    $(".search-results").html(res);
                } else {
                    $(".search-results").empty();
                }
            }
        });
    });

    $('#stream').change(function(){
        var stream_uuid = $(this).val();
        if(stream_uuid){
            $.ajax({
                type:"GET",
                url:"/admin/get-subject-list?stream_uuid="+stream_uuid,
                success:function(res){
                    if(res){
                        $("#subject").empty();
                        $.each(res,function(key,value){
                            $("#subject").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#subject").empty();
                    }
                }
            });
            }else{
            $("#subject").empty();
        }
    });
    $(document).on('change','#packageListStream',function(){
        var package_uuid = $(this).val();
        if(package_uuid){
            $.ajax({
                type:"GET",
                url:"/admin/get-package-prices?package_uuid="+package_uuid,
                success:function(res){
                    if(res){
                        $("#packagePrice").empty();
                        $.each(res,function(key,value){
                            $("#packagePrice").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#packagePrice").empty();
                    }
                }
            });
        }else{
            $("#subject").empty();
        }
    });

    $('#studentListPackage').change(function(){
        var user_uuid = $(this).val();
        if(user_uuid){
            $.ajax({
                type:"GET",
                url:"/admin/get-package-by-stream?uuid="+user_uuid,
                success:function(res){
                    if(res){
                        $("#packageListStream").empty();
                        $.each(res,function(key,value){
                            $("#packageListStream").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#packageListStream").empty();
                    }
                }
            });
        }else{
            $("#subject").empty();
        }
    });

    $('#streamList').change(function(){
        var stream_uuid = $(this).val();
        if(stream_uuid){
            $.ajax({
                type:"GET",
                url:"/admin/get-subject-list?stream_uuid="+stream_uuid,
                success:function(res){
                    if(res){
                        $("#subject").empty();
                        $.each(res,function(key,value){
                            $("#subjectList").append('<label class="control outlined control-radio radio-primary">'+value+
                                '                                    <input name="subject_uuid" value='+key+' type="radio" >\n' +
                                '                                    <div class="control-indicator"></div>\n' +
                                '                                </label>');
                        });

                    }else{
                        $("#subject").empty();
                    }
                }
            });
        }else{
            $("#subject").empty();
        }
    });

    $('#country').change(function(){
        var countryID = $(this).val();
        if(countryID){
            $.ajax({
                type:"GET",
                url:"/get-state-list?country_id="+countryID,
                success:function(res){
                    if(res){
                        $("#state").empty();
                        $("#state").append('<option>Select</option>');
                        $.each(res,function(key,value){
                            $("#state").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#state").empty();
                    }
                }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }
    });
    $('#state').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:"GET",
                url:"/get-city-list?state_id="+stateID,
                success:function(res){
                    if(res){
                        $("#city").empty();
                        $.each(res,function(key,value){
                            $("#city").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#city").empty();
                    }
                }
            });
        }else{
            $("#city").empty();
        }

    });

    $('#userTypeList').on('change',function(){
        var role = $(this).val();
        if(role){
            $.ajax({
                type:"GET",
                url:"/get-user-type-list?role="+role,
                success:function(res){
                    if(res){
                        $("#userList").empty();
                        $.each(res,function(key,value){
                            $("#userList").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#userList").empty();
                    }
                }
            });
        }else{
            $("#userList").empty();
        }

    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photograph-preview img').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#photograph").change(function(){
        readURL(this);
    });
    //
    $(document).on('change','.filterChange',function (){
        $('#filter-form').submit();
    });

});



