$(function() {

  var handheld = $('.display-phone').css('display') !== 'none',
      offset   = 50,
      lnk_sel  = "a[rel='external']"; /* a[href^='http'], a[href^='//'], */

  // mark external links
  $('header,section,div.modal,footer').find(lnk_sel).each(function() {
    var $el = $(this);

    if ($el.hasClass('img') || $el.hasClass('btn')) {
      $el.externalLinks({
          favicon      : false,
          span_class   : '',
          link_class   : '',
          title_text   : "{title}"
      });
    } else {
      $el.externalLinks({
          favicon      : false,
          title_text   : "{title}"
      });
    }
  });

  $('body') .attr('data-spy', 'scroll')
            .attr('data-target', '.navbar')
            .attr('data-offset', offset)
            .scrollspy();

  // simple anchor scrolling
  $("a[href^='#']").each(function(n) {
      var me   = $(this),
          href = me.attr('href'),
          them;

      if (href === '#top' && handheld)
        href = '#intro';

      them = $(href === '#' ? 'body' : "a[name='" + href.substring(1) + "']");

      if (them.length) me.click(function(e) {
          e.preventDefault();
          $('html,body').animate({"scrollTop": (href === '#top' ? 0 : them.offset().top - offset)}, 'slow');
      });
  });

  // make selects look like bootstap-dropdowns (but not for IE)
  if (!$.browser.msie) $('select').each(function(i, e) {
    var $e = $(e);

    if (($e.data('convert') !== 'yes'))
      return;

    $e.hide().wrap('<div class="btn-group" id="select-group-' + i + '" />');

    var select  = $('#select-group-' + i),
        current = ($e.val()) ? $e.val() : '&nbsp;';

    select.html('<input type="hidden" data-index="0" value="' + $e.val() + '" name="' + $e.attr('name') + '" id="' + ($e.attr('id') || ('select-group-' + i)) + '" /><a class="btn" href="javascript:;">' + current + '</a><a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a><ul class="dropdown-menu"></ul>');

    $e.find('option').each(function(o, q) {
      var $q = $(q);
      select.find('.dropdown-menu').append('<li><a href="javascript:;" data-index="' + o + '" data-value="' + $q.attr('value') + '">' + $q.text() + '</a></li>');
      if ($q.attr('selected'))
        select.find('.dropdown-menu li:eq(' + o + ')').click();
    });

    select.find('.dropdown-menu a').click(function() {
      var me = $(this);
      select.find('input[type=hidden]').val(me.data('value')).data('index', me.data('index')).change();
      select.find('.btn:eq(0)').text(me.text());
    });
  });

  // activate carousels - add caption resizing logic so that there are all the same height
  $(window).resize((function(c){

    var fn = function() {
      var mapFn = c.find('.active .carousel-caption').height() < 0 ? Math.min : Math.max;

      c.each(function() {
        var $this = $(this),
            caps  = $this.find('.carousel-caption').height('auto'),
            items = $this.find('.item').not('.active,.prev,.next').addClass('measure');

        var hgts = caps.map(function () { return $(this).height(); }).get(),
            max  = mapFn.apply(null, hgts);

        items.removeClass('measure');

        if (!isNaN(max))
          caps.height(max);

      });
    };

    fn();
    return fn;

  })($('.carousel').carousel()));

  /* google maps */
  window.buildmap = function() {

    if (!google) return;

    var mdiv   = $("#gmap"),
        mrcdiv = $("#gmap_route_ctrls"),
        desc   = $("#gmap_route_cnt"),
        btns   = {
            r: mrcdiv.find('button#gmap_showroute_btn'),
            d: mrcdiv.find('button#gmap_showroutedesc_btn')
        };

    if (!mdiv.length || !mrcdiv.length || !btns.r.length || !btns.d.length)
        return;

    btns.d.parent().invisible();

    var touch       = $('html').hasClass('touch'),
        markers     = [],
        addrs       = [],
        imgs        = {
            marker: function() { return new google.maps.MarkerImage('/assets/img/mapmarker.png', new google.maps.Size(32, 37), undefined, new google.maps.Point(16, 37)); },
            shadow: function() { return new google.maps.MarkerImage('/assets/img/mapmarker-shadow.png', new google.maps.Size(51, 37), undefined, new google.maps.Point(16, 37)); }
        },

        tmodes      = [
            google.maps.TravelMode.BICYCLING,
            google.maps.TravelMode.WALKING,
            google.maps.TravelMode.TRANSIT,
            google.maps.TravelMode.DRIVING
        ],

        resizeFn    = function() {
            var w = mdiv.width();
            if (mdiv.data('last-width') !== w) {
                map.fitBounds(bounds);
                mdiv.data('last-width', w);
            }
        },

        routeFn     = function(e) {
            e.preventDefault();

            var fval, tval, mval,
                from = mrcdiv.find('[name="from"]'),
                fgrp = from.closest('.btn-group'),
                to   = mrcdiv.find('[name="to"]'),
                tgrp = from.closest('.btn-group'),
                mode = mrcdiv.find('[name="mode"]');

            if (!from.length || !to.length || !mode.length)
                return;

            fval = from.val();
            tval = to.data('index');
            mval = mode.data('index');

            if (!fval.length) {
                fgrp.addClass('error');
                btns.d.hide();
                return;
            } else {
                fgrp.removeClass('error');
            }

            if (!markers[ tval ]) {
                tgrp.addClass('error');
                return;
            } else {
                tgrp.removeClass('error');
            }

            btns.r.button('loading');
            btns.d.parent().invisible();
            desc.empty();

            dirservice.route({
                origin      : fval,
                destination : markers[ tval ].getPosition(),
                travelMode  : tmodes[ mval ]
            }, function(res, status) {
                btns.r.button('reset');

                if (status == google.maps.DirectionsStatus.OK) {
                    dirrenderer.setDirections(res);
                    btns.d.parent().visible();
                } else if (window.console) {
                    fgrp.addClass('error');
                    btns.d.parent().invisible();
                    console.log('DirectionRequest failed', status, res);
                }
            });
        },

        dirservice  = new google.maps.DirectionsService(),
        bounds      = new google.maps.LatLngBounds(),
        geocoder    = new google.maps.Geocoder(),

        map         = new google.maps.Map(mdiv[0], {
            styles              : [ { featureType: "poi", stylers: [ { gamma: 1 }, { lightness: 5 }, { saturation: 1 }, { visibility: "off" } ] },{ featureType: "poi.park", stylers: [ { visibility: "simplified" } ] },{ featureType: "water", elementType: "labels", stylers: [ { visibility: "simplified" } ] },{ featureType: "transit.station.airport", stylers: [ { visibility: "off" } ] },{ featureType: "landscape", stylers: [ { visibility: "simplified" } ] },{ featureType: "road", elementType: "geometry", stylers: [ { visibility: "simplified" }, { saturation: 1 }, { lightness: 90 }, { gamma: 0.18 } ] } ],
            scrollwheel         : false,
            draggable           : !touch,
            tilt                : 45,
            zoom                : 12,
            center              : new google.maps.LatLng(51.924216,4.481776),
            mapTypeId           : google.maps.MapTypeId.ROADMAP,
            overviewMapControl  : false,
            mapTypeControl      : false,
            panControl          : !touch,
            rotateControl       : false,
            streetViewControl   : false
        }),

        dirrenderer = new google.maps.DirectionsRenderer({
            draggable               : false,
            map                     : map,
            panel                   : $('#gmap_route_cnt')[0],
            suppressInfoWindows     : true,
            suppressMarkers         : true,
            suppressBicyclingLayer  : true
        });

    $('.adr').each(function() {
        var $me  = $(this),
            addr = [];

        $.each(['.street-address', '.postal-code', '.locality'], function(idx, sel) {

            var t = $me.find(sel).text();

            if (t && t.length)
                addr.push(t);
        });

        if (addr.length)
            addrs.push(addr.join(','));
    });

    $.each(addrs, function(idx, addr) {

        var slot = idx;
        geocoder.geocode({'address': addr, 'region': 'nl'}, function(res, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                // map.setCenter(results[0].geometry.location);

                var marker = new google.maps.Marker({
                    map         : map,
                    icon        : imgs.marker(),
                    shadow      : imgs.shadow(),
                    position    : res[0].geometry.location
                });

                bounds.extend(marker.getPosition());
                markers[slot] = marker;

            } else if (window.console) {
                console.log('Geocode was not successful for the following reason: ' + status, res);
            }

            if (addrs.length === $.grep(markers, function(el) { return !!el; }).length) {
                resizeFn();
                $(window).resize(resizeFn);
                btns.r.click(routeFn);
            }
        });
    });
  };

  // contact form validation
  (function(cf) {
    if (!cf || !cf.validate)
      return;

    var btn = cf.find('button'),
        fbk = $('#contact_feedback'),
        thk = $('#contact_thanks');

    cf.attr('novalidate','novalidate')
      .ajaxForm({
        beforeSubmit: function() {
          var r = cf.valid();

          if (r) {
            fbk.hide();
            btn.button('loading');
          }

          return r;
        },

        success     : function(resp) {
          if (!resp.success) {
            btn.button('reset');

            if (resp.errmsg)
              fbk.show().text(resp.errmsg);
          } else {
            cf.hide();
            thk.show();
          }
        },

        error       : function(resp) {}
    })
      .validate({
        rules     : {
          name    : {
            minlength : 2,
            required  : true
          },
          email   : {
            required  : true,
            email     : true
          },
          message : {
            minlength : 10,
            required  : true
          }
        }
      });

  })($('#contactform'));

  // load some more js - async style
  (function(d, w) {
    var maps = d.createElement("script"),
        plus = d.createElement('script'),
        body = $('body'),
        prot = w.location.protocol;

    maps.type  = "text/javascript";
    maps.async = true;
    maps.src   = prot + "//maps.googleapis.com/maps/api/js?v=3&sensor=false&key=AIzaSyA0_Gk1edG8XQXZyol5z89xcA3g7rq1zGA&callback=buildmap";

    plus.type  = 'text/javascript';
    plus.async = true;
    plus.src   = prot + '//apis.google.com/js/plusone.js';

    body.append(plus);
    body.append(maps);

    // g+ badge config
    w.___gcfg = {
      lang      : $('html').attr('lang') || 'nl',
      parsetags : 'onload'
    };

  })(document, window);
});