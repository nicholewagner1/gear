<?php
require($_SERVER['DOCUMENT_ROOT'].'/api/auth.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gear Tracker
        <?php echo $title; ?></title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/utils/DataTables/datatables.min.css">
    <script type="text/javascript" charset="utf8" src="/utils/DataTables/datatables.min.js"></script>
    <link rel="stylesheet" href="/utils/multiselect-11/css/select2.min.css">
    <link href="/utils/fontawesome-free-6.5/css/fontawesome.css" rel="stylesheet">
    <link href="/utils/fontawesome-free-6.5/css/solid.css" rel="stylesheet">
    <script src="/js/common.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/site.webmanifest">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    if ($_SERVER['HTTP_HOST'] === 'gearcheck.localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    } else {
        require($_SERVER['DOCUMENT_ROOT'].'/api/auth_api.php');
        $session = $auth0->getCredentials();
        $userInfo = $auth0->getUser();
        $userId = $userInfo['sub'];

        if ($session === null || !getUserRole($userId)) {
            // The user isn't logged in
            include($_SERVER['DOCUMENT_ROOT'].'/views/login.php');
            //	header("Location: https://gear.nicholewagner.com/login.php");
            die;
        }
    }

?>


    <main>
        <div id="pageBody" class="container">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/">
                    <img class="navbar-brand" style="height:60px;" src="/images/logo.png" alt="Gear Tracker Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">

                            <a class="nav-link" href="/index.php"><i class="fa-solid fa-guitar"></i></a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="itemnNav" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </a>
                            <div class="dropdown-menu" aria-labelledby="itemnNav">
                                <a class="dropdown-item" href="/item.php?action=add"><i class="fa-solid fa-circle-plus"></i> Add</a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/?filter=checked_in&value=0&view=grid">Currently Out</a>

                                <a class="dropdown-item" href="/?filter=category&value=Instrument&view=grid"">Instruments</a>
		  <a class=" dropdown-item" href="/?filter=category&value=Live%Sound"><i class="fa-solid fa-volume-high"></i> Live Sound</a>
                                <a class="dropdown-item" href="/?filter=category&value=Microphone"><i class="fa-regular fa-microphone-lines"></i> Microphones</a>

                                <a class="dropdown-item" href="/?filter=category&value=Case"><i class="fa-solid fa-cart-flatbed-suitcase"></i> Cases</a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/?missing=asset_tag"><i class="fa-solid fa-barcode"></i></i> Missing Asset Tag</a>
                                <a class="dropdown-item" href="/?filter=insured&value=1"><i class="fa-solid fa-vault"></i></i> Insured</a>

                                <div class="dropdown-divider"></div>
                            </div>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="/maintenance.php?action=add"><i class="fa-solid fa-screwdriver-wrench"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/packing_list.php"><i class="fa-solid fa-cart-flatbed-suitcase"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="itemnNav" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </a>
                            <div class="dropdown-menu" aria-labelledby="itemnNav">

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="/packing_list.php?action=view&id=2"><i class="fa-solid fa-volume-high"></i> Solo Acoustic</a>
                                <a class="dropdown-item" href="/packing_list.php?action=view&id=1"><i class="fa-regular fa-microphone-lines"></i> Solo Acoustic - Need PA</a>

                                <a class="dropdown-item" href="/packing_list.php?action=view&id=15"><i class="fa-solid fa-cart-flatbed-suitcase"></i> Band Gig</a>
                                <a class="dropdown-item" href="/packing_list.php?action=view&id=4"><i class="fa-solid fa-cart-flatbed-suitcase"></i> Band Gig - Need PA</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/packing_list.php?action=add"><i class="fa-solid fa-circle-plus"></i> Add</a>

                            </div>
                        <li class="nav-item">
                            <a class="nav-link" href="/profit_loss.php"><i class="fa-solid fa-piggy-bank"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="itemnNav" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </a>
                            <div class="dropdown-menu" aria-labelledby="itemnNav">

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="/profit_loss.php?action=new"><i class="fa-solid fa-plus"></i> New</a>
                                <a class="dropdown-item" href="/venue.php"><i class="fa-solid fa-building-columns"></i> Venues</a>

                            </div>
                        <li class="nav-item">
                            <a class="nav-link" href="/reports.php"><i class="fa-solid fa-chart-pie"></i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="history.back()"><i class="fa-solid fa-backward"></i></a>
                        </li>
                    </ul>

                </div>
            </nav>
            <div class="row mt-3">
                <div class="col">