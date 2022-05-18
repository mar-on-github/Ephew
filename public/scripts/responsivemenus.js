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