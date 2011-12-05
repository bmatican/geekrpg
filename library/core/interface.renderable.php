<?php

  /**
   * Used to designate anything that can be printed (htmlElements, views, etc)
   * NOTE: Not implemented yet. Need to fix requirment chain
   */
  interface Renderable {
    public function toString( $checkPermission );
  }

?>