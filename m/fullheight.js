var fixgeometry = function() {
    scroll(0, 0);
	
	$(".hidden").hide();
	
    var header = $(".header:visible");
    var footer = $(".footer:visible");
    var content = $(".content:visible");
    var viewport_height = $(window).height();
    
    var content_height = viewport_height - header.outerHeight() - footer.outerHeight();
    
    content_height -= (content.outerHeight() - content.height());
    content.height(content_height);
	
	/* freeplay qbox */
	
	var qbox = $(".qbox:visible");
	var notqbox = $(".notqbox:visible");
	
	var qbox_height = content.height()-notqbox.outerHeight();
	qbox_height -= (qbox.outerHeight() - qbox.height());
	qbox_height -= (viewport_height - header.outerHeight() - content.height())+15;
	qbox.height(qbox_height);
  };

  $(document).ready(function() {
    $(window).bind("orientationchange resize pageshow", fixgeometry);
  });