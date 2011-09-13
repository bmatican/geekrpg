(function($){
   
   $.jTooltip = {
      options  : {
         /**
          * Returns the position of the tooltip relative to the targeted object
          * @param {jQuery} tooltip    The tooltip in use (this)
          * @param {jQuery} target     The targeted element
          * @return {Object}  Returns an object that will be used as .css( object ) on the tooltip
          */
         position    : function( tooltip, target ){ 
            return { 
               right : 0,
               top   : target.outerHeight()
            };
         },
         // The class of the tooltip
         className   : 'jTooltip'
      }
   }
   
   $.fn.jTooltip = function( options ){
      
      var opt = {};
      $.extend( opt, $.jTooltip.options, options );
      
      this.each( function(){
         $(this)
            .attr( 'jTooltip', $(this).attr('title') )
            .removeAttr( 'title' );
      });
      
      return this
         .wrap( $(document.createElement('span')).css({'position':'relative'}) )
         .each(function(){
            $(this).bind( 'focus.jTooltip', function(){
               var tooltip = $(document.createElement('div'));
               tooltip
                  .addClass( opt.className )
                  .html( $(this).attr('jTooltip') )
                  .prependTo( $(this).parent() )
                  .css( opt.position( tooltip, $(this) ) );
            }).bind( 'blur.jTooltip', function(){
               $(this).siblings('.'+opt.className).remove();
            });
      });
      
   }
   
})(jQuery);
