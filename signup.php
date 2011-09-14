<!DOCTYPE html>
<html lang="en">
   <head>
      <title>RPGeek - ALPHA</title>
      <link rel="stylesheet" type="text/css" href="css/main.css" />
      <script type="text/javascript" src="js/jquery.js"></script>
      <script type="text/javascript" src="js/jquery.cookie.js"></script>
      <link rel="stylesheet" type="text/css" href="css/jTooltip.css" />
      <script type="text/javascript" src="js/jquery.jTooltip.js"></script>
      <script type="text/javascript" src="js/default.js"></script>
   </head>
  
   <body>
      
      <header id="header">
         <h1>RPGeek - <span style="color:lightgreen">ALPHA</span></h1>
         <form action="login.php" method="post" id="login">
            <div>
               <input type="text" name="username" size="10" placeholder="username" title="Username" />
               <input type="password" name="password" size="10" placeholder="password" title="Password" />
               <input type="submit" value="Log In" title="GO!" />
            </div>
         </form>
         <nav id="menu">
            <ul>
               <li><a href="#">Menu Item</a></li>
               <li><a href="#">Another Menu Item</a></li>
               <li><a href="#">Yet Another Menu Item</a></li>
               <li><a href="#">Item</a></li>
            </ul>
         </nav>
      </header>
      
      <section id="content">
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
      </section>
      
      <footer id="footer">
         (C) CS Club 2011
      </footer>
      
   </body>
</html>
