<?php

declare(strict_types=1);

require "vendor/autoload.php";

use view\preset_UI;
$preset_UI = new preset_UI();
?>

<html lang="en">
<head>
    <title>Torbok - Podols</title>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
    <script src="js/sound.js"></script>
</head>
<body>
<div class="navbar">
    Torbok - Podols
</div>
<div class="menubar">
    <form method="get">
        <?php
        $preset_UI->parsePresetList();
        ?>
    </form>
</div>
<div class="main">
    <div class="content">
        <?php
        $preset_UI->parsePresetData();
        $preset_UI->addPresetUI();
        ?>
        <br/>
        <?php
        $preset_UI->deletePresetUI();
        ?>
        <br/>
        <?php
        $preset_UI->editPreset();
        ?>
    </div>
    <svg class="soundInputSvg" preserveAspectRatio="none" id="visualizer" version="1.1"
         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <defs>
            <mask id="mask">
                <g id="maskGroup"></g>
            </mask>
            <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#ff0a0a;stop-opacity:1"/>
                <stop offset="20%" style="stop-color:#f1ff0a;stop-opacity:1"/>
                <stop offset="90%" style="stop-color:#d923b9;stop-opacity:1"/>
                <stop offset="100%" style="stop-color:#050d61;stop-opacity:1"/>
            </linearGradient>
        </defs>
        <rect x="0%" y="0%" width="100%" height="100%" fill="url(#gradient)" mask="url(#mask)"></rect>
    </svg>
    <div id="welcome">
    </div>
</div>
</body>
</html>