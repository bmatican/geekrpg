<?php

  function formatTime( array $arr ){
    /*
    foreach( $arr as $k => $v ){
      $arr[ $k ] = $v <= 9 ? '0'.$v : $v;
    }
    */
    return $arr['days'].'d '.$arr['hours'].'h '.$arr['minutes'].'m '.$arr['seconds'].'s ago';
  }

  function timeVals( $timestamp ){
    $a['hours']   = floor( $timestamp / 3600 );
    $timestamp    = $timestamp % 3600;
    $a['minutes'] = floor( $timestamp / 60 );
    $a['seconds'] = $timestamp % 60;
    $a['days']    = floor( $a['hours'] / 24 );
    $a['hours']   = $a['hours'] % 24;
    return $a;
  }

?>
