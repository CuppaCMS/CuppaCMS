<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <title>FileManager</title>
        <link rel="shortcut icon" sizes="24x24" href="media/images/icon.png" />
        <!-- Responsive -->
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- -->
    </head>
    <body>
        <top-bar></top-bar>
        <list-comp></list-comp>
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
        <script src="js/scripts.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <script src="https://unpkg.com/@webcomponents/webcomponentsjs@2.2.6/webcomponents-loader.js"></script>
        <script>
            cuppa.requiereComponent(["components/topBar/TopBar.html"], {plainComponent:true});
            cuppa.requiereComponent(["components/list/List.html"], {plainComponent:true});
            cuppa.requiereComponent(["components/modals/Rename.html"], {plainComponent:true});
            cuppa.requiereComponent(["components/modals/ShowURL.html"], {plainComponent:true});
            cuppa.requiereComponent(["components/modals/Preview.html"], {plainComponent:true});
            cuppa.requiereComponent(["components/modals/Alert.html"], {plainComponent:true});
        </script>
    </body>
</html>