$(function(){
   
  $('input[title]').tTitle({
    
  });
  
  $('#notifications')
    .each(function(){ 
      var container = $(document.createElement('div'));
      container
        .append( '<div style="text-align:center"><img src="images/ajax.gif" alt="Loading..." height="16" /></div>' )
        .css({
          position    : 'absolute',
          right       : 0,
          top         : '105%',
          width       : '200px',
          padding     : 5,
          border      : '1px solid #ccc',
          background  : 'rgba(255, 255, 255, 1)',
          color       : '#111'
        });
        
      $(this)
        .append( container )
        .css({
          position  : 'relative'
        })
        .data('isVisible', false)
        .data('container', container);
        
      container.hide();
    })
    .bind('click.toggleNotifications', function(e){
      e.preventDefault();
      var container = $(this).data('container');
      if( $(this).data('isVisible') ){  
        container.hide();
      } else {
        container
          .html( '<div style="text-align:center"><img src="images/ajax.gif" alt="Loading..." height="16" /></div>' )
          .show();
        setTimeout(function(){
          container.html( 'Not implemented yet, but it would look nice if it were :)' );
        }, 1000);
      }
      
      $(this).data( 'isVisible', !$(this).data('isVisible') );
    });
  
});

