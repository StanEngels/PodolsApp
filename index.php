<html>
      <head>
        <?php include_once("includes/version.php") ?>
        <title>Torbok - Podols</title>
        <link rel="stylesheet" href="<?php echo auto_version('/css/style.css'); ?>" type="text/css" />
        <script src="<?php echo auto_version('/js/sound.js') ?>"></script>
      </head>
    <body>
        <div class="navbar">
          <h1>Torbok - Podols</h1>
        </div>
        <div class="menubar">
          <h1>Torbok - Podols</h1>
        </div>
      <svg preserveAspectRatio="none" id="visualizer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <defs>
            <mask id="mask">
                <g id="maskGroup">
              </g>
            </mask>
            <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="90%">
                <stop offset="0%" style="stop-color:#ff0a0a;stop-opacity:1" />
                <stop offset="20%" style="stop-color:#f1ff0a;stop-opacity:1" />
                <stop offset="90%" style="stop-color:#d923b9;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#050d61;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect x="0%" y="0%" width="100%" height="100%" fill="url(#gradient)" mask="url(#mask)"></rect>
      </svg>
      <div id="text"></div>
    </body>
    </html>