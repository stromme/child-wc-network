var map;
var default_lat_lng = {'us': {'lat': 39.7741861, "lng" : -101.8009427}, 'ca': {'lat': 57.55361079999999, "lng" : -102.2047349}};
var markers = [];
var plot_markers = [];
var infowindow;
var recenter = '';

$(document).ready(function(){
  /**
   * Selecting country, also change state selector
   */
  $('.select-location .select-country li>a').click(function(e){
    e.preventDefault();
    var current = $(this);
    var current_country = current.attr('data-country');
    var parent = current.closest('.select-country');
    var selector = $('.dropdown-toggle', parent);
    var temp = selector.attr('data-country');
    selector.attr('data-country', current_country);
    var html = ((current_country=='US')?'United States':'Canada')+' <span class="caret"></span>';
    selector.html(html);
    html = ((current_country=='US')?'State':'Province')+' <span class="caret"></span>';
    if(current_country=='CA'){
      map.setCenter(new google.maps.LatLng(default_lat_lng.ca.lat, default_lat_lng.ca.lng));
    }
    else {
      map.setCenter(new google.maps.LatLng(default_lat_lng.us.lat, default_lat_lng.us.lng));
    }
    var state_province_selector = $('.select-location .select-state-province');
    $('.dropdown-toggle', state_province_selector).html(html);
    $('.dropdown-toggle', state_province_selector).attr('data-state-province', '');
    current.attr('data-country', temp);
    html = (temp=='US')?'United States':'Canada';
    current.html(html);
    var list_container = $('.dropdown-menu', state_province_selector);
    var current_list = $('li', list_container);
    var template_container = $('#state-province-templates');
    var new_list = $('li', template_container);
    template_container.append(current_list);
    list_container.append(new_list);

    var list = $('.city-list');
    $('li.not-found', list).hide();
    $('li[data-country!="'+current_country+'"]', list).slideUp('fast');
    $('li[data-country="'+current_country+'"]', list).slideDown('fast');

    // GA tracking
    if(typeof ga!='undefined' && ga){
      ga('send', 'event', 'Locations', 'Choose country', html);
    }
  });

  /**
   * Fix select state/province selection div positioning on small screen
   */
  $('.select-location .select-state-province, #member-application .select-state-province').click(function(e){
    var parent = $(this);
    var menu = $('.dropdown-menu', parent);
    if(menu){
      menu.css('left', 0);
      var menu_left = parent.offset()['left'];
      var menu_width = menu.outerWidth();
      var window_width = $(window).width();
      if(menu_left+menu_width>window_width){
        var new_left = menu_left+menu_width-window_width;
        menu.css('left', (new_left*-1)-10);
      }
    }
  });

  /**
   * Lookup location
   */
  $('.select-location button').click(function(e){
    e.preventDefault();
    var button = $(this);
    wc_location_lookup(button);
  });

  /**
   * Updating selection head after selecting
   */
  $(document).on('click', '.select-location .select-state-province li>a', function(e){
    e.preventDefault();
    var current = $(this);
    var current_state_province = current.attr('data-state-province');
    var parent = current.closest('.select-state-province');
    var selector = $('.dropdown-toggle', parent);
    selector.attr('data-state-province', current_state_province);
    var html = current.html()+' <span class="caret"></span>';
    selector.html(html);

    //var button = $('.select-location button');
    //wc_location_lookup(button);
    var country = $('.select-location .select-country .dropdown-toggle').attr('data-country');
    var state_province = $('.select-location .select-state-province .dropdown-toggle').attr('data-state-province');
    var list = $('.city-list');
    $('li.not-found', list).hide();
    $('li[data-country!="'+country+'"]', list).slideUp('fast');
    $('li[data-country="'+country+'"][data-state!="'+state_province+'"]', list).slideUp('fast');
    $('li[data-country="'+country+'"][data-state="'+state_province+'"]', list).slideDown('fast');
    setTimeout(function(){
      if($('li:visible', list).length<=0) $('li.not-found', list).slideDown();
      else {
        var elm = $('a', $('li:visible', list).first());
        var lat = elm.attr('data-lat');
        var lng = elm.attr('data-lng');
        if(lat && lng){
          var position = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
          map.panTo(position);
          map.setCenter(position);
        }
      }
    }, 300);


    // GA tracking
    if(typeof ga!='undefined' && ga){
      ga('send', 'event', 'Locations', 'Choose state', current.html());
    }
  });

  /**
   * Track on selecting city from list
   */
  $(document).on('click touchstart', '.city-list a', function(){
    var selected = $(this);
    var name = selected.text();
    ga('send', 'event', 'Locations', 'Choose country', name);
  });

  google.maps.event.addDomListener(window, 'load', initialize_map);

  $(document).on('mouseenter', '.city-list-container .city-list li>a', function(){
    var elm = $(this);
    var lat = elm.attr('data-lat');
    var lng = elm.attr('data-lng');
    if(lat && lng){
      var position = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
      recenter = map.getCenter();
      map.panTo(position);
    }
  });
  /*$(document).on('mouseleave', '.city-list-container .city-list li>a', function(){
    if(recenter!=''){
      map.panTo(recenter);
    }
  });*/
});

/**
 * Windowcleaning location do lookup
 *
 * @param button
 */
function wc_location_lookup(button){
  var country = $('.select-location .select-country .dropdown-toggle').attr('data-country');
  var state_province = $('.select-location .select-state-province .dropdown-toggle').attr('data-state-province');
  if(state_province && state_province!=''){
    if(!button.hasClass('disabled')){
      button.addClass('disabled');
      var result = $('.city-list');
      result.fadeOut('fast', function(){
        $(this).html('');
        $(this).show();
        var data = {
          action: 'select_windowcleaning_location',
          'country'  : country,
          'state_province'  : state_province,
          '_nonce': button.attr('data-nonce')
        };
        var post = new AjaxPost(data, {
          'spinner': new LoadingSpinner({
            'reference_elm': result,
            'insert_method': 'after',
            'loader_style': 'position:relative;display:inline-block;width:35px;height:55px;vertical-align:middle;',
            'spinner_style': 'top:30px;left:0;position:absolute;'
          })
        },
        // Ajax replied
        function(json_response){
          try {
            var response = JSON.parse(json_response);
            if(response.status==1){
              /*if(markers.length>0){
                while(markers.length>0){
                  var marker = markers.pop();
                  marker.setMap(null);
                }
              }*/
              result.append(response.html);
              /*if(response.markers.length>0){
                var pin = new google.maps.MarkerImage(response.pin, null, null, null, new google.maps.Size(70,45));
                $(response.markers).each(function(){
                  var marker = this;
                  var marker_pos = new google.maps.LatLng(marker.lat, marker.lng);
                  var plot_marker = new google.maps.Marker({
                    position: marker_pos,
                    map: map,
                    title: marker.company_name,
                    animation: google.maps.Animation.DROP,
                    icon: pin
                  });
                  markers.push(plot_marker);
                  var contentString = '<a href="'+marker.url+'" target="_blank" class="marker-hover" style="position:relative;display:block;padding:5px;border-radius:5px;color:#333;font-weight:200;font-size:18px;text-decoration:none;"><span style="font-weight:500;">'+marker.city+',</span> <span style="color:#666;">'+marker.state+'</span><span style="display:block;font-size:14px;color:#66AFD4;">'+((marker.company_name)?marker.company_name:'')+'</span></a>';
                  google.maps.event.addListener(plot_marker, 'click', function() {
                    //map.setZoom(11);
                    //map.panTo(plot_marker.getPosition());
                    if (infowindow) infowindow.close();
                    infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });
                    infowindow.open(map,plot_marker);
                  });
                });
              }*/
              map.setZoom(7);
              if(response.map_center!=''){
                var position = new google.maps.LatLng(response.map_center.lat, response.map_center.lng);
                recenter = position;
                map.panTo(position);
                map.setCenter(position);
              }
            }
            else {
              bootstrap_alert(json_response.message, 'error');
            }
          } catch(e){
            bootstrap_alert('Connection error', 'error');
          }
          button.removeClass('disabled');
        },
        // Ajax post error (means connection error)
        function(){
          bootstrap_alert('Connection error', 'error');
          button.removeClass('disabled');
        });
        post.doAjaxPost();
      });
    }
  }
  else {
    bootstrap_alert('Please select '+((country=='US')?'state':'province'), 'error');
  }
}

/**
 * Initialize google map
 */
function initialize_map() {
  var mapOptions = {
    center: new google.maps.LatLng(default_lat_lng.us.lat, default_lat_lng.us.lng),
    //disableDefaultUI: true,
    zoom: 5
  };
  map = new google.maps.Map(document.getElementById("google-maps"), mapOptions);
  if(locations_var['wc_locations']){
    var styles = [{
      url: locations_var['cluster_pin']['small'],
      width: 80,
      height: 42,
      textColor: '#ffffff',
      textSize: 16
    }, {
      url: locations_var['cluster_pin']['medium'],
      width: 80,
      height: 67,
      textColor: '#ffffff',
      textSize: 16
    }, {
      url: locations_var['cluster_pin']['large'],
      width: 80,
      height: 61,
      textColor: '#ffffff',
      textSize: 16
    }];
    var pin = new google.maps.MarkerImage(locations_var['pin'], null, null, null, new google.maps.Size(70,45));
    $(locations_var['wc_locations']).each(function(){
      if(this.lat!='' && this.lng!='' && !this.del){
        var marker_pos = new google.maps.LatLng(this.lat, this.lng);
        var plot_marker = new google.maps.Marker({
          position: marker_pos,
          map: map,
          title: this.city+', '+this.state,
          animation: google.maps.Animation.DROP,
          icon: pin
        });
        var contentString = '<a href="'+this.url+'" target="_blank" class="marker-hover" style="position:relative;display:block;padding:5px;border-radius:5px;color:#333;font-weight:200;font-size:18px;text-decoration:none;"><span style="font-weight:500;">'+this.city+',</span> <span style="color:#666;">'+this.state+'</span><span style="display:block;font-size:14px;color:#66AFD4;">'+((this.company)?this.company:'')+'</span></a>';
        google.maps.event.addListener(plot_marker, 'click', function() {
          if (infowindow) infowindow.close();
          infowindow = new google.maps.InfoWindow({
              content: contentString
          });
          infowindow.open(map,plot_marker);
        });

        plot_markers.push(plot_marker);
      }
    });
    //if (marker_clusterer) {
      //marker_clusterer.clearMarkers();
    //}
    marker_clusterer = new MarkerClusterer(map, plot_markers, {
      gridSize: 30,
      styles: styles
    });
  }
}