<?php

if($_POST['opml']) {
    $opml = @simplexml_load_string($_POST['opml']);
}
elseif($_FILES['opml']) {
    $opml = @simplexml_load_file($_FILES['opml']['tmp_name']);
}
elseif($_GET['source']) {
    show_source(__FILE__);
    exit;
}

if(is_object($opml)) {
    $csv = tmpfile();
    foreach((array) $opml->xpath('//outline') as $i => $element) {
        $element = (array) $element;
        if($i == 0) {
            fputcsv($csv, array_keys($element['@attributes']));
        }
        fputcsv($csv, $element['@attributes']);
    }
    fseek($csv, 0, SEEK_END);
    $filesize = ftell($csv);
    fseek($csv, 0);
    if($filesize > 0) {
        header("Content-Type: text/csv");
        header("Content-Length: $filesize");
        header("Content-Disposition: attachment; filename=opml.csv");
        fpassthru($csv);
        exit;
    }
}

?><html>
<head>
    <title>OPML to CSV converter</title>
    <style type="text/css">

        body {
         background-color: #fff;
         margin: 40px;
         font-family: Lucida Grande, Verdana, Sans-serif;
         font-size: 14px;
         color: #4F5155;
        }
        
        a {
         color: #003399;
         background-color: transparent;
         font-weight: normal;
        }
        
        h1 {
         color: #444;
         background-color: transparent;
         border-bottom: 1px solid #D0D0D0;
         font-size: 16px;
         font-weight: bold;
         margin: 24px 0 2px 0;
         padding: 5px 0 6px 0;
        }
        
        code {
         font-family: Monaco, Verdana, Sans-serif;
         font-size: 12px;
         background-color: #f9f9f9;
         border: 1px solid #D0D0D0;
         color: #002166;
         display: block;
         margin: 14px 0 14px 0;
         padding: 12px 10px 12px 10px;
        }
        
        table {
         width:100%;
         font-family: Monaco, Verdana, Sans-serif;
         font-size: 12px;
         background-color: #f9f9f9;
         border: 1px solid #D0D0D0;
         color: #002166;
         margin: 14px 0 14px 0;
         padding: 12px 2%;
        }
        td, th {
         padding:4px;
        }
        th {
         border-bottom:3px solid #D0D0D0;
        }
        
        dd code { cursor:pointer; }
        
        .error {
         background:pink;
         padding:1px 12px;
         font-weight:bold;
         margin:12px 0;
         color:black;
         border:1px solid red;
        }
        
    </style>
</head>

<body>

    <h1>OPML to CSV converter</h1>
    <p><abbr title="Outline Processor Markup Language">OPML</abbr> is an <abbr title="eXtensible Markup Language">XML</abbr> format often used for blogrolls and lists of news feeds in RSS or ATOM format.</p>
    <p>This utility provides an easy way to convert OPML to <abbr title="Comma-Separated Values">CSV</abbr> format for importing into Microsoft Excel, database tables, and other applications where a simple, tabular format is needed.</p>

    <form method="post" enctype="multipart/form-data">
        <p><b>Upload OPML file:</b></p>
        <p><input type="file" name="opml" /></p>
        <p>- or -</p>
        <p><b>Paste OPML file contents:</b></p>
        <p><textarea name="opml" rows="20" cols="80"></textarea></p>
        <p><input type="submit" value="Convert to CSV" />
    </form>

    <p>Created on 12/12/2009 by <a href="http://jonathonhill.net">Jonathon Hill</a>. No Rights Reserved. | <a href="?source=true">View Source</a></p>

</body>
</html>
