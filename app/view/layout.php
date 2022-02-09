
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->e($title)?></title>
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="../app/src/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="../app/src/css/app.bundle.css">
<!--    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">-->
    <link id="myskin" rel="stylesheet" media="screen, print" href="../app/src/css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="../app/src/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../app/src/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="../app/src/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="../app/src/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="../app/src/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="../app/src/css/fa-regular.css">
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">-->
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="/ProjectComponents/public/users"><img alt="logo" class="d-inline-block align-top mr-2" src="../app/src/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/ProjectComponents/public/users">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/ProjectComponents/public/logout">Выйти</a>
            </li>
        </ul>
    </div>
</nav>
<?= $this->section('content')?>
</body>
</html>