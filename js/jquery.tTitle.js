(function($){
  
  $.tTitle = {
    // Just a predefined set of position functions. Their names are self-explanatory :)
    POSITIONS : {
      topLeft : function( tooltip, target ){
        return {
          top   : -tooltip.outerHeight(),
          left  : 0
        };
      },
      topCenter : function( tooltip, target ){
        return {
          top   : -tooltip.outerHeight(),
          left  : (target.outerWidth() - tooltip.outerWidth()) / 2
        };
      },
      topRight : function( tooltip, target ){
        return {
          top   : -tooltip.outerHeight(),
          right : 0
        };
      },
      right : function( tooltip, target ){
        return {
          right : -tooltip.outerWidth(),
          top   : (target.outerHeight() - tooltip.outerHeight()) / 2
        };
      },
      bottomRight : function( tooltip, target ){
        return {
          bottom  : -target.outerHeight(),
          right   : -target.outerWidth()
        };
      },
      bottomCenter : function( tooltip, target ){
        return {
          bottom  : -target.outerHeight(),
          left  : (target.outerWidth() - tooltip.outerWidth()) / 2
        };
      },
      bottomLeft : function( tooltip, target ){
        return {
          bottom  : -target.outerHeight(),
          left    : 0
        };
      },
      left : function( tooltip, target ){
        return {
          left  : -tooltip.outerWidth(),
          top   : (target.outerHeight() - tooltip.outerHeight()) / 2
        };
      },
    },
    // Default options
    options  : {
      /**
       * Returns the position of the tooltip relative to the targeted object
       * @param {jQuery} tooltip   The tooltip in use (this)
       * @param {jQuery} target    The targeted element
       * @return {Object}  Returns an object that will be used as .css( object ) on the tooltip
       */
      position   : function( tooltip, target ){ 
        return { 
          right : 0,
          top   : target.outerHeight()
        };
      },
    },
    // Default classes
    classes : {
      main : 'tTitle'
    }
  }
  
  $.fn.tTitle = function( options, classes ){
    
    var opt = {};
    var cls = {};
    
    $.extend( opt, $.tTitle.options, options );
    $.extend( cls, $.tTitle.classes, classes );
    
    this.each( function(){
      $(this)
        .attr( 'tTitle', $(this).attr('title') )
        .removeAttr( 'title' );
    });
    
    return this
      .wrap( $(document.createElement('span')).css({'position':'relative'}) )
      .each(function(){
        $(this).bind( 'focus.tTitle', function(){
          var tooltip = $(document.createElement('div'));
          tooltip
            .addClass( cls.main )
            .html( $(this).attr('tTitle') )
            .prependTo( $(this).parent() )
            .css( opt.position( tooltip, $(this) ) )
            .hide()
            .fadeIn();
        }).bind( 'blur.tTitle', function(){
          $(this).siblings('.'+cls.main).remove();
        });
    });
    
  }
  
})(jQuery);
