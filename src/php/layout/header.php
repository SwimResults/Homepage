
<header id="header">
    <div class="header-title">
        <a href="/">
            <img class="header-logo" src="images/logo.svg" alt="SwimResults Logo">
            <span class="header-title-text">SwimResults</span>
        </a>
    </div>

    <script>
        let menuShown = false;
        function toggleMenu() {
            menuShown = !menuShown;

            if (menuShown) {
                document.getElementById("header").classList.add("menu-shown");
            } else {
                document.getElementById("header").classList.remove("menu-shown");
            }
        }
    </script>

    <button class="menu-btn" onclick="toggleMenu()" id="menuToggleButton">
        <img class="icon-show" src="images/icon/menu.svg" alt="Menu">
        <img class="icon-hide" src="images/icon/close.svg" alt="Close">
    </button>

    <?php include('php/layout/nav.php'); ?>
</header>