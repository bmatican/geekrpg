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
    
    <div style="margin:auto; text-align:right; width: 330px;">
      <?php 
        echo $form->input('username', array('placeholder'=>'username', 'title'=>'What\'s yr name?'));>
        echo $form->input('password', array('placeholder'=>'username', 'title'=>'Yr secret passcode bitte'));>
        echo '
          <label for="login_remember" class="checkboxLabel">
            <input type="checkbox" id="login_remember" name="remember" />
            Remember me!
          </label>
        ';
        echo $form->input('submit', array('placeholder'=>'username', 'title'=>'... proceed... if you dare!', 'value' => 'Train your geekyness'));>
      ?>
    </div>
    
      </tr>
    </table>
  </section>
<?php
  $form->close();
?>
