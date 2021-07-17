<?php require_once "api/core/core.php";
// if (!empty($_SESSION)) {
//     // Boot them back to login if we don't have a session set up.
//     if (!isset($_SESSION['nemesys']['username']) && $_SESSION['nemesys']['has_cts_access'] == 1) {
//         header("Location: login.php");
//     }
// } else {
//     header("Location: login.php");
// }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="../../css/fontawesome/css/all.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <title>CVN 77 Correspondence Tracking System</title>
</head>
<body>

<header>
    <!-- <h1 class="header">CVN 77 Correspondence Tracking System</h1> -->
    <img src="img/logo.png" id="cts-logo" title="CVN 77 Correspondence Tracking System">
</header>
<?php include("inc/navigation.php"); // include vice actual nav-bar because space. ?>

<?php
    // Check to see if there's a specific view requested, but also which page is being fetched.
    if (isset($_GET['view'])) $view = $_GET['view'] . "/";
    if (isset($_GET['page'])) {
        if (($view == "admin" || $view == "dev") && (!IS_ADMIN || IS_DEVELOPER)) {
            // User isn't authorized to make admin changes; show them the home page.
            include("pages/home.php");
        }
        @include("pages/$view" . $_GET['page'] . ".php");
    } else {
        // If nada, just show the landing page.
        include("pages/home.php");
    }

?>

<footer>
    <span class="subtle">CVN 77 <strong>2020</strong></span>
</footer>

</body>
</html>