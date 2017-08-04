<?php require 'config.php';
    $file = $_REQUEST['dir'];
    $filesAr = explode('/',$file);
    $ffs  = scandir($scan_dir.$file);
    
    if(count($ffs) && $ffs!==false){
        echo '<!DOCTYPE HTML> <html> <head> <title>Files on Localhost</title> <meta charset="utf-8" /> <link rel="stylesheet" href="'.$APP_URL.'assets/css/main.css" /> <style> .command {font-size: 15px;}</style> </head> <body> <p> &nbsp;<i class="fa fa-folder-open"></i>:'.$scan_dir;
        $fileNew = '';$first=1;
        foreach($filesAr as $fileA){
            if($fileA){
                $fileNew.= str_replace('//','/',$fileA.'/');
                echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$fileNew) . '" title="Jump to folder '.$fileA.'">' . $fileA . '</a> /';
            }
        }
        echo '</p> <br/><div style="margin:10px 20px;text-align:left;float:left;width:100%">';
        foreach ($ffs as $ff) {
            if (!in_array($ff,array('.','..','.metadata','.buildpath','.htpasswd','.project','.svn') )) {
                echo '<div style="float:left;width:33%">';
                if( is_dir($scan_dir.str_replace('//','/',$file.'/'. $ff)) ){
                    echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$file.'/'. $ff) . '">' . $ff . '</a> <i class="fa fa-folder"></i>';
                }else{
                    echo $ff;
                    echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$file.'/'. $ff) . '"> <i class="fa fa-download" title="Download File"></i></a> ';
                    echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$file.'/'. $ff) . '"> <i class="fa fa-eye" title="View source in Plain text format"></i></a> ';
                    echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$file.'/'. $ff) . '&edit=2"> <i class="fa fa-paint-brush" title="View Highlighted Source"></i></a> ';
                    echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$file.'/'. $ff) . '&edit=1"> <i class="fa fa-pencil" title="Edit Source in Plain Text"></i></a> ';
                    echo '<a href="'.$HOST.'file.php?dir=' .str_replace('//','/',$file.'/'. $ff) . '&edit=1"> <i class="fa fa-pencil-square" title="Edit Source in Highlighted Text"></i></a> ';
                }
                
                echo '</div>';
            }
        }
        echo '</div></body> </html>';
    }else{
        if( ( $fileData = file_get_contents( $scan_dir.$file ) ) ) {
            $d = strrchr( $file, '/' );
            $d = substr( $d, 1, strlen( $d ) );

            $filename = basename($d);
            $file_extension = strtolower(substr(strrchr($filename,"."),1));

            switch( $file_extension ) {
                case "gif" : $ctype = "image/gif";  $content_type = 1; break;
                case "png" : $ctype = "image/png";  $content_type = 1; break;
                case "jpeg":
                case "jpg" : $ctype = "image/jpeg"; $content_type = 1; break;
                case "ico" : $ctype = "image/ico";  $content_type = 1; break;

                case "js"  : $ctype = "application/javascript; charset=utf-8"; $content_type = 2; break;
                case "css" : $ctype = "text/css; charset=utf-8";   $content_type = 2; break;
                case "html": $ctype = "text/plain; charset=utf-8"; $content_type = 2; break;
                default    : $ctype = "text/plain; charset=utf-8"; $content_type = 2; 
            }

            if($content_type == 1 ) {
                header('Content-type: ' . $ctype);
                readfile($scan_dir.$file); 
            }else {
                if($_REQUEST['edit']==1){
                    echo '<!DOCTYPE HTML> <html> <head> <title>Files on Localhost</title> <meta charset="utf-8" />  <style> .command {font-size: 15px;}</style> 
                    <link rel="stylesheet" href="'.$APP_URL.'vendors/codemirror/codemirror.css">
                    <link rel="stylesheet" href="'.$APP_URL.'vendors/codemirror/foldgutter.css">
                    <link rel="stylesheet" href="'.$APP_URL.'vendors/codemirror/dialog.css">
                    <link rel="stylesheet" href="'.$APP_URL.'vendors/codemirror/monokai.css">
                    <link rel="stylesheet" href="'.$APP_URL.'vendors/codemirror/show-hint.css">
                    <script src="'.$APP_URL.'vendors/codemirror/codemirror.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/show-hint.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/javascript-hint.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/css-hint.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/html-hint.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/searchcursor.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/search.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/dialog.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/matchbrackets.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/closebrackets.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/comment.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/hardwrap.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/foldcode.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/brace-fold.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/javascript.js"></script>
                    <script src="'.$APP_URL.'vendors/codemirror/sublime.js"></script>
                    </head> <body> <p><b>Editing File:</b> '.$scan_dir.$file.'</p> <br/><textarea style="margin:10px 20px;width:95%;height:600px" id="editor" name="editor">'.$fileData.'</textarea>';
                    echo '</body> <style type="text/css"> .CodeMirror {border-top: 1px solid #eee; border-bottom: 1px solid #eee; line-height: 1.3; height: 500px} .CodeMirror-linenumbers { padding: 0 8px; }</style>
                <script type="text/javascript">
                 var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
                      lineNumbers: true,
                      //mode: "text/html",
                      extraKeys: {"Ctrl-Space": "autocomplete"},
                      matchBrackets: true
                    });
                </script></html>';
                 }elseif($_REQUEST['edit']==2){
                    echo '<!DOCTYPE HTML> <html> <head> <title>Files on Localhost</title> <meta charset="utf-8" />  <style> .command {font-size: 15px;}</style> 
                    </head> <body> <p><b>Highlighting File:</b> '.$scan_dir.$file.'</p> <br/><pre><code class="">'.htmlspecialchars($fileData).'</code></pre>';
                    echo '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/default.min.css"><script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/highlight.min.js"></script><script>hljs.initHighlightingOnLoad();</script>';
                     echo '</body> <style type="text/css"> .CodeMirror {border-top: 1px solid #eee; border-bottom: 1px solid #eee; line-height: 1.3; height: 500px} .CodeMirror-linenumbers { padding: 0 8px; }</style>
                </html>';
                 }else{
                    header('Content-type: ' . $ctype);
                    echo $fileData;
                 }
                
               
            }
            //file_put_contents( $d, $fileData );
        }else{
            die('file not found');
        }
    }

