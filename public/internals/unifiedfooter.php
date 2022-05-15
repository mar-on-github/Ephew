<?php
if (isset($usedefaultsidebar) AND $usedefaultsidebar === 'true') {
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
<script>
  function unrollbottombar() {
    var x = document.getElementById("mybottombar");
    if (x.className === "bottombar") {
      x.className += " responsive";
    } else {
      x.className = "bottombar";
    }
  }

  function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
  }

  function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
  }
</script>
</body>

</html>