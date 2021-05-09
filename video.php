<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотреть</title>
</head>
<body>
    <form id="yt-form">
        <input type="text" id="yt-link">
        <input type="submit">
    </form>
    
    <!-- <iframe width="1190" height="669" src="https://www.youtube.com/embed/mT1jrGDrT1I?autoplay=1" allow="autoplay" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        <script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>
    <script>
        function parse_url(url) {
            // parses str url for different parts: host, port, etc.0
            var parser = document.createElement('a');
            parser.style.display = "none";
            parser.href = url;
            return parser;
        }

        function getParameterByName(name, url='') {
            // helps to get url parameter by its name
            if (!url) return;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        function create_frame(id) {
            // creates iframe with specified youtube video ID
            $('#yt-form').remove();
            var iframe = document.createElement('iframe');
            var link = 'https://www.youtube.com/embed/' + id + '?autoplay=1';
            // SET FRAME OPTIONS THERE
            var el = '<iframe width="1190" height="669" src="' + link + '" allow="autoplay" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            $('body').append(el);
        }

        $('#yt-form').submit(function(e) {
            var link = $("#yt-link").val();
            var yt_id = '';
            var RegExp = /^((http|https):\/\/)?(www\.)?([A-Za-zА-Яа-я0-9]{1}[A-Za-zА-Яа-я0-9\-]*\.?)*\.{1}[A-Za-zА-Яа-я0-9-]{2,8}(\/([\w#!:.?+=&%@!\-\/])*)?/;
            // many checks for different types of links to get the youtube video id
            // accepted links formats: youtu.be..., youtube.com/watch?v=..., youtube.com/watch/... 
            if(RegExp.test(link) & link.indexOf('youtu') != -1) {
                parser = parse_url(link);
                if (parser.search.indexOf('v') != -1) {
                    yt_id = getParameterByName('v', link);
                }
                else {
                    var pieces = parser.pathname.split('/');
                    yt_id = pieces[pieces.length-1];
                    if (!yt_id) yt_id = pieces[pieces.length-2];
                }
            }

            // if yt_id is false or null - the link is bad
            if (yt_id) create_frame(yt_id);
            else alert('У вас ссылка кривая');
            // preventing std form handler
            e.preventDefault();
        });
    </script>
</body>
</html>