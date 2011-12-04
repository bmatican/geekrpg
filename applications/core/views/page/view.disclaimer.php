<?php

  class Disclaimer extends GeekView{

    public function __construct( $args ){
      $this->add( <<<HTML
<h3>Contact information:</h3>

<ul>
 <li><b>b.matican_at_jacobs-university.de</b></li>
 <li><b>s.mirea_at_jacobs-universirt.de</b></li>
</ul>

<p>
  For each external link existing on this website, we initially have checked that the target page does not contain contents which is illegal wrt. German jurisdiction. However, as we have no influence on such contents, this may change without our notice. Therefore we distance ourselves from the contents of any website referenced through our external links.
</p>

<p>
  This website is student lab work and does not necessarily reflect Jacobs University Bremen opinions. Jacobs University Bremen does not endorse this site, nor is it checked by Jacobs University Bremen regularly, nor is it part of the official Jacobs University Bremen web presence.
</p>
HTML
        );
    }
  }

?>