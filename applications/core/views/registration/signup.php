<?php
  $argNames = array( 'username', 'password1', 'password2', 'email' );
  $args = $this->getViewArgs();
  if( isset($args['result']) && $args['result'] ){
    echo "User <b>".$args['username']."</b> registered successfully!";
  } else {
    foreach( $argNames as $v ){
      $$v = isset( $args[$v] ) ? implode(', ', $args[$v]) : '';
    }
    $form = new Form( 'registration/signup', $argNames );
    $form->open(array(
      'id'            => 'register',
      'autocomplete'  => 'off'
    ));
?>
  <h1>
    Register-a-Geek Form
  </h1>
  <section>
    <table align="center">
      <tr>
        <td><input type="text" name="username" placeholder="username" title="Input your desired username" /></td>
        <td class="error" id="username_err"><?php echo $username; ?></td>
      </tr>
      <tr>
        <td><input type="text" name="email" placeholder="E-Mail" title="Type in your email" /></td>
        <td class="error" id="email_err"><?php echo $email; ?></td>
      </tr>
      <tr>
        <td><input type="password" name="password1" placeholder="password" title="Choose your password" /></td>
        <td class="error" id="password_err"><?php echo $password1; ?></td>
      </tr>
      <tr>
        <td><input type="password" name="password2" placeholder="re-type password" title="Re-type your above chosen password" /></td>
        <td class="error" id="password2_err"><?php echo $password2; ?></td>
      </tr>
      <tr>
        <td align="right"><input type="submit" value="B A Geek" title="proceed..." /></td>
        <td class="error" id="submit_error">&nbsp;</td>
      </tr>
    </table>
  </section>
<?
  $form->close();
  }
?>
