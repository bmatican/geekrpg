<?php
  $form = new Form( 'registration', 'registration/login' );
  $form->open( array(
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
        <td><input type="text" name="username" placeholder="username" title="What's you're username?" /></td>
        <td class="error" id="username_err">&nbsp;</td>
      </tr>
      <tr>
        <td><input type="password" name="password1" placeholder="password" title="Input password to be logged :)" /></td>
        <td class="error" id="password_err">&nbsp;</td>
      </tr>
      <tr>
        <td align="right">
          <label for="login_remember" class="checkboxLabel">
            <input type="checkbox" id="login_remember" name="remember" />
            Remember me!
          </label>
        </td>
      </tr>
      <tr>
        <td align="right"><input type="submit" value="Train your geekyness" title="... proceed... if you dare!" /></td>
        <td class="error" id="submit_error">&nbsp;</td>
      </tr>
    </table>
  </section>
<?php
  $form->close();
?>
