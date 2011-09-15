<form action="src/signup.php" method="post" id="register" autocomplete="off">
  <h1>
    Register-a-Geek Form
  </h1>
  <section>
    <table align="center">
      <tr>
        <td><input type="text" name="username" placeholder="username" title="Input your desired username" /></td>
        <td class="error" id="username_err">&nbsp;</td>
      </tr>
      <tr>
        <td><input type="text" name="email" placeholder="E-Mail" title="Type in your email" /></td>
        <td class="error" id="email_err">&nbsp;</td>
      </tr>
      <tr>
        <td><input type="password" name="password1" placeholder="password" title="Choose your password" /></td>
        <td class="error" id="password_err">&nbsp;</td>
      </tr>
      <tr>
        <td><input type="password" name="password2" placeholder="re-type password" title="Re-type your above chosen password" /></td>
        <td class="error" id="password2_err">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><input type="submit" value="B A Geek" title="proceed..." /></td>
        <td class="error" id="submit_error">&nbsp;</td>
      </tr>
    </table>
  </section>
</form>
