<?php
if (isset($usedefaultsidebar) and $usedefaultsidebar === 'true') {
  if (!isset($autoendcontentdiv) or !($autoendcontentdiv === 'false')) {
    echo "</div>";
  }
}
?>
<div class="bottombar" id="mybottombar">
  <a href="javascript:void(0);" class="icon" onclick="unrollbottombar()">&#9776;</a>
  <?php
  bottombarlink("/home/", "Home");
  if (!isset($_SESSION["username"])) {
    bottombarlink("/login/", "Log in");
    bottombarlink("/signup/", "Sign up");
  } else {
    bottombarlink("/logout/", "Log out");
  }
  //bottombarlink("/feedback/", "Feedback");
  bottombarlink("/blog/", "Blog");
  include('styleswitcher.php')
  ?>
</div>

</body>

<script src="/scripts/responsivemenus.js"></script>

</html>