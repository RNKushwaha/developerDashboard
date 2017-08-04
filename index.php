<?php require 'config.php';
if (!isset($_SESSION['result'])) $_SESSION['result'] = "";

if ( isset($_POST['action']) && $_POST['action']=='edit') {
    $stmt = $sqlite->prepare("UPDATE login_urls SET username = :username, password = :password WHERE id = :id");
    $stmt->bindValue(':username', $_POST['username']);
    $stmt->bindValue(':password', $_POST['password']);
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
    
    // $databaseErrors = $stmt->errorInfo();
    // if (!empty($databaseErrors)) {
    //     _pr($databaseErrors);
    //     return $databaseErrors;
    // }
    
    return 1;
}

if ( isset($_POST['action']) && $_POST['action']=='remove') {
    $stmt = $sqlite->prepare("DELETE FROM login_urls WHERE id = :id");
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
    
    // $databaseErrors = $stmt->errorInfo();
    // if (!empty($databaseErrors)) {
    //     _pr($databaseErrors);
    //     return $databaseErrors;
    // }
    
    return 1;
}

if ( isset($_POST['action']) && $_POST['action']=='add') {
    $stmt = $sqlite->prepare('INSERT INTO login_urls(website,login_url,username,password) VALUES(:website,:login_url,:username,:password)');
    $stmt->execute([
        ':website'   => $_POST['website'],
        ':login_url' => $_POST['login_url'],
        ':username'  => $_POST['username'],
        ':password'  => $_POST['password'],
    ]);

    $sqlite->lastInsertId();
    
    // $databaseErrors = $stmt->errorInfo();
    // if (!empty($databaseErrors)) {
    //     _pr($databaseErrors);
    //     return $databaseErrors;
    // }
    
    return 1;
}

if (isset($_POST['submit'])) {
    switch ($_POST['encode']) {
        case 'md5' : $result = md5($_POST['command']);break;
        case 'urlencode' : $result = urlencode($_POST['command']);break;
        case 'urldecode' : $result = urldecode($_POST['command']);break;
        case 'base64_encode' : $result = base64_encode($_POST['command']);break;
        case 'base64_decode' : $result = base64_decode($_POST['command']);break;
        case 'sha1' : $result = sha1($_POST['command']);break;
        case 'sha256' : $result = hash('sha256', $_POST['command']);break;
        case 'run_command' : $result = shell_exec($_POST['command']);break;
        case 'run_php' : $result = shell_exec("php -r '" . $_POST['command'] . "'");break;
        case 'parse' : $result = shell_exec('php -l ' . $_POST['command']);break;
        case 'get_file' : break;
        case 'source' :break;
        case 'sql' : 
            $db = new PDO("mysql:host=localhost;dbname=".$_POST['db'], 'root', 'root');
            $command  = 'mysql --host=localhost --user=root --password=root --database=' .$_POST['db'].' --execute=" ';
            $results  = shell_exec($command . $_POST['command'].'"');
            $results1 = "<div style='overflow-x:scroll;width:100%'><table style='border:1px solid #ccc;border-collapse:collapse;font-size:14px;color:#333;text-align:left;' cellpadding='4' cellspcaing='1' border='1'><tr><td style='border:1px solid #ccc;padding:3px 5px;'>"; 
            $results2 = str_replace("\n","</td></td><tr><td style='border:1px solid #ccc;white-space:pre;padding:3px 5px;'>",$results);
            $results3 = str_replace("\t","</td><td style='border:1px solid #ccc;white-space:pre;padding:3px 5px;'>",$results2);
            $results4 ='</td></tr></table></div>';
            $result   = $results1.$results3.$results4;
            break;
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $site_title;?></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="<?php echo $APP_URL;?>assets/css/main.css" />
        <style type="text/css"> 
            .command {font-size: 15px;}.border{border:1px solid #ccc;white-space:pre;padding:3px 5px;}
            .container_class{margin:10px 20px;text-align:left;float:left;clear:both;display: block;width:96%;}
            .copyText{float:right;cursor: pointer;}
            .children{float:left;width:33%;}
             #outer{position: fixed;width: 60%;right: 10px; bottom: 10px;display: block;z-index: 999;}
            .msg_box {position: relative; min-height: 50px; height: auto; padding: 10px;display: block; margin-bottom: 5px; }
            .msg_box h3{ font-size: 25px;margin:3px 10px 3px 0;color: #fff;font-weight: bold; display: inline-block;}
            .red_bg{ color:#fff; background: red; }
            .green_bg{ color:#fff; background: green; }
        </style>
    </head>
    <body>
        <div id="outer"></div>
        <div id="header">
            <div class="top">
                <div id="logo">
                    <span class="image"><img src="<?php echo $APP_URL;?>assets/images/rn.kushwaha.jpg" alt="img" style="height:50px;width:50px;border-radius:25px" /></span>
                    <h1 id="title">RN Kushwaha</h1>
                    <p>Sr. Software Engineer<br/>OnSumaye Web Solutions Pvt. Ltd.</p>
                </div>

                <!-- Nav -->
                <nav id="nav">
                    <ul>
                        <li><a href="#top" id="top-link" class="skel-layers-ignoreHref"><span class="icon fa-list">Websites</span></a></li>
                        <li><a href="#command" id="command-link" class="skel-layers-ignoreHref"><span class="icon fa-edit">Commands</span></a></li>
                        <li><a href="<?php echo $HOST;?>db.php?username=root&db=commloan" target="_blank" class="skel-layers-ignoreHref"><span class="icon fa-database">Database</span></a></li>
                        <li><a href="#passwords" class="skel-layers-ignoreHref"><span class="icon fa-lock">Passwords</span></a></li>
                        <li><a href="#urls" class="skel-layers-ignoreHref"><span class="icon fa-lock">Most Visited URLs</span></a></li>
                    </ul>
                </nav>

            </div>

            <div class="bottom">
                <ul class="icons">
                    <li><a href="https://www.google.co.in" target="_blank" class="icon fa-google"><span class="label">Google</span></a></li>
                    <li><a href="https://platform.commloan.com/projects/commloan/issues?set_filter=1" target="_blank" class="icon fa-bug"><span class="label">Issues</span></a></li>
                    <li><a href="https://ops.onsumaye.com/index.php?m=tasks" target="_blank" class="icon fa-edit"><span class="label">OPS</span></a></li>
                    <li><a href="https://www.stackoverflow.com" target="_blank" class="icon fa fa-stack-overflow"><span class="label">Stackoverflow</span></a></li>
                    <li><a href="https://www.github.com" target="_blank" class="icon fa-github"><span class="label">Github</span></a></li>
                    <li><a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="icon fa-envelope"><span class="label">Gmail</span></a></li>
                    <li><a href="javascript:;" class="icon fa fa-arrow-left" id="hide_sidebar"><span class="label"></span></a></li>
                </ul>
            </div>
        </div>
        <a href="javascript:;" class="icon fa fa-arrow-right" id="show_sidebar" style="position:fixed;top:25%;left:-300px;background-color:#333;color:#fff;" ><span class="label"></span></a>
        <div id="main">
            <section id="top" class="one1 dark1 cover1">
                <header>
                    <h2>Local Websites</h2>
                </header>
            <?php $scanned_dirs = scandir($scan_dir);
            echo '<div class="container_class">';
            $j=1;

            foreach ($scanned_dirs as $scanned_dir) {
                if (is_dir($scan_dir.$scanned_dir) && !in_array( $scanned_dir, $excluded_folders )) {
                    echo '<div class="children">';
                    if (array_key_exists($scanned_dir, $mapper)){
                        echo '<a href="' . $mapper[$scanned_dir] . '" target="_blank" title="Open Website">' . $scanned_dir . '</a> '
                            . '<a href="'.$HOST.'file.php?dir='.$scanned_dir.'" target="_blank"><i class="fa fa-pencil-square-o" title="Open File Manager"></i></a>';
                    } else {
                        echo '<a href="'.$HOST.$scanned_dir . '" target="_blank" title="Open Website">' . $scanned_dir . '</a> '
                            . '<a href="'.$HOST.'file.php?dir=' . $scanned_dir .'" target="_blank"><i class="fa fa-pencil-square-o" title="Open File Manager"></i></a>';
                    }
                    echo '</div>';
                    if($j%3==0){
                        echo '</div><div class="container_class">';
                    }
                    $j++;
                }
            }
            echo '</div>';
            ?>
            </section>
            <section id="top" class="one1 dark1 cover1">
                <header>
                    <h2>Live Websites</h2>
                </header>
                <div class="container_class">
                     <div class="children">
                         <a href="https://www.commloan-test.com/login" target="_blank" title="Open Website">COMMLOAN TEST</a>
                     </div>
                     <div class="children">
                          <a href="https://www.commloan-staging.com/login" target="_blank" title="Open Website">COMMLOAN STAGING</a>
                      </div>
                      <div class="children">
                          <a href="https://www.commloan-qa.com/login" target="_blank" title="Open Website">COMMLOAN QA</a>
                      </div>
                </div>
                <div class="container_class">
                     <div class="children">
                         <a href="https://www.commloan.com/login" target="_blank" title="Open Website">COMMLOAN LIVE</a>
                     </div>
                     <div class="children">
                         
                      </div>
                      <div class="children">
                      </div>
                </div>
            </section>

            <section id="urls" class="one1 dark1 cover1">
                <header>
                    <h2>Most Visited URLs</h2>
                </header>
                <div class="container_class">
                     <div class="children">
                         <a href="https://www.commloan-localhost.com/component/lender/lender?layout=requests&status=2" target="_blank" title="Open Website">LENDER ONBOARD REVIEW</a>
                     </div>
                     <div class="children">
                          <a href="https://www.commloan-localhost.com/component/lender/index.php?option=com_lender&view=lender&layout=summary&lenderID=374&onboard=63&dashboardStatus=2" target="_blank" title="Open Website">LENDER ONBOARD SUMMARY 63</a>
                      </div>
                      <div class="children">
                          <a href="https://www.commloan-localhost.com/component/lender/lender?layout=underwriting&lenderID=374&onboard=63&dashboardStatus=2&cat=1" target="_blank" title="Open Website">LENDER ONBOARD REVIEW 63</a>
                      </div>
                </div>
                
            </section>

            <section id="passwords" class="one1 dark1 cover1">
                <header>
                    <h2>Passwords</h2>
                </header>
                 <table border="1" cellspacing="1" cellpadding="5" style="margin-left: 2px;width:99%" id="password_table">
                <tr>
                    <td class="border">ID</td>
                    <td class="border">Website</td>
                    <td class="border">Username</td>
                    <td class="border">Password</td>
                </tr>
                <?php foreach( $sqlite->query("SELECT * FROM login_urls ORDER BY id ASC") as $row){?>
                    <tr>
                        <td class="border"><?php __($row['id']);?></td>
                        <td class="border"><a href="<?php __($row['login_url']);?>" target="_blank"><?php __($row['website']);?></a></td>
                        <td class="border"><?php __($row['username']);?><i class="fa fa-file-o copyText" data-type="Username"></i></td>
                        <td class="border"><?php __($row['password']);?><i class="fa fa-file-o copyText" data-type="Password"></i></td>
                    </tr>
                <?php }?>
                </table>
            </section>
            <!-- command -->
            <section id="command" class="four">
                <div class="container">

                    <header> <h2>Command</h2> </header>

                    <form method="post">
                        <div class="row">
                            <div class="12u$ command">
                                <textarea name="command" class="small" placeholder="Command"><?php echo htmlspecialchars($_REQUEST['command']); ?></textarea>
                                <input type="radio" class="radio" name="encode" value="urlencode" <?php if ($_REQUEST['encode'] == 'urlencode') echo 'checked'; ?>> urlencode 
                                <input type="radio" class="radio" name="encode" value="urldecode" <?php if ($_REQUEST['encode'] == 'urldecode') echo 'checked'; ?>> urldecode 
                                <input type="radio" class="radio" name="encode" value="base64_encode" <?php if ($_REQUEST['encode'] == 'base64_encode') echo 'checked'; ?>> Base64 encode 
                                <input type="radio" class="radio" name="encode" value="base64_decode" <?php if ($_REQUEST['encode'] == 'base64_decode') echo 'checked'; ?>> Base64 decode 
                                <input type="radio" class="radio" name="encode" value="md5" <?php if ($_REQUEST['encode'] == 'md5') echo 'checked'; ?>> md5 
                                <input type="radio" class="radio" name="encode" value="sha1" <?php if ($_REQUEST['encode'] == 'sha1') echo 'checked'; ?>> sha1 
                                <input type="radio" class="radio" name="encode" value="sha256" <?php if ($_REQUEST['encode'] == 'sha256') echo 'checked'; ?>> sha256 
                                <input type="radio" class="radio" name="encode" value="run_command" <?php if ($_REQUEST['encode'] == 'run_command') echo 'checked'; ?>> run command 
                                <input type="radio" class="radio" name="encode" value="run_php" <?php if ($_REQUEST['encode'] == 'run_php') echo 'checked'; ?>> run php 
                                <input type="radio" class="radio" name="encode" value="source" <?php if ($_REQUEST['encode'] == 'source') echo 'checked'; ?>> File source 
                                <input type="radio" class="radio" name="encode" value="parse" <?php if ($_REQUEST['encode'] == 'parse') echo 'checked'; ?>> parse php
                                <input type="radio" class="radio" name="encode" value="get_file" <?php if ($_REQUEST['encode'] == 'get_file') echo 'checked'; ?>> get file
                                <input type="radio" class="radio" name="encode" value="sql" <?php if ($_REQUEST['encode'] == 'sql') echo 'checked'; ?>> sql
                                <input type="radio" class="radio" name="encode" value="keepalive" <?php if ($_REQUEST['encode'] == 'keepalive') echo 'checked'; ?>> keepalive
                            </div>
                            <div class="8u$ pull-left" id="db" <?php if ($_REQUEST['encode'] != 'db') echo ' style="display:none"'; ?>>
                                <label class="pull-left">Database &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <select name="db" class="select pull-left">
                                    <option value="">Select Database</option>
                                    <?php foreach( $db->query("SHOW DATABASES") as $row){
                                        if($row['Database']==$_REQUEST['db']) $selected = 'selected';else $selected='';
                                            echo '<option value="'.$row['Database'].'" '.$selected.'>'.$row['Database'].'</option>';
                                        }?>
                                </select>
                            </div>
                            <div class="8u$ pull-left" id="keepalive" style="width:100%;<?php if ($_REQUEST['encode'] != 'keepalive') echo ' display:none'; ?>">
                                <label class="pull-left">Duration to call &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <input type="text" id="keepalive_dur" name="keepalive_dur" value="<?php echo $_REQUEST['keepalive_dur'];?>" style="float:left;padding:5px;width:100px;margin-right:20px"> 
                                <span style="float:left;display:inline-block;margin-right:20px">in seconds</span>
                                <span id="timer" style="float:left;display:inline-block;margin-right:20px"></span>
                                <span id="requests" style="float:left;display:inline-block"></span>
                            </div>
                            <div class="12u$ pull-right">
                                <input type="submit" value="Submit" name="submit" />
                            </div>	
                        </div>
                        <div class="row">
                            <div class="12u$" id="command_result" <?php if($_REQUEST['encode']=='source'){ echo 'style="text-align:left;font-size:14px;border:5px solid #333;margin-left:15px;"';}?>>
                                <?php if($_REQUEST['encode']=='sql'){
                                        echo $result;
                                    }elseif($_REQUEST['encode']=='source'){
                                        show_source($_POST['command']);
                                    }elseif($_REQUEST['encode']=='get_file'){
                                        include_once('aryan/simple_html_dom.php');
                                        $url =  strlen($_REQUEST['command'])>0 ? urldecode($_REQUEST['command']) : 'http://te.tournamentsoftware.com/ranking/category.aspx?id=10503&category=515';
                                        $html = file_get_html($url);
                                        $images = $scripts = $stylesheets = $anchors = $selects = $tables = $iframes = '';
                                        foreach($html->find('a') as $e)       $anchors.=     '<tr><td>'.$e->href.'</td><td>'.$e->class.'</td><td>'.$e->id.'</td></tr>';
                                        foreach($html->find('link') as $e)    $stylesheets.= '<tr><td>'.$e->href.'</td><td>'.$e->rel.'</td><td>'.$e->type.'</td></tr>';
                                        foreach($html->find('script') as $e)  {$scripts.=    '<tr><td>';if(strlen($e->src) >0) $s=$e->src;else $s='inline javascript';$scripts.=$s.'</td><td>'.$e->type.'</td></tr>';}
                                        foreach($html->find('img') as $e)     $images.=      '<tr><td>'.$e->src.'</td><td>'.$e->class.'</td><td>'.$e->id.'</td></tr>';
                                        foreach($html->find('iframe') as $e)  $iframes.=     '<tr><td>'.$e->src.'</td><td>'.$e->class.'</td><td>'.$e->id.'</td></tr>';
                                        foreach($html->find('table') as $table){ 
                                            $trs = '';
                                            foreach($table->find('tr') as $tr) {
                                                $trs.= '<tr class="'.$tr->class.'" id="'.$tr->id.'">';
                                                $tds = '';
                                                foreach($tr->find('td') as $td) {
                                                    $tds.= '<td class="'.$td->class.'" id="'.$td->id.'">'.$td->plaintext.'</td>';
                                                }
                                                $trs.= $tds.'</tr>';
                                            }
                                            $tables.= '<tr><td><table cellspacing="1" cellpadding="5" style="border:1px solid #ccc;" border="1" id="'.$table->id.'" class="'.$table->class.'">'.$trs.'</table></td><td>'.$table->class.'</td><td>'.$table->id.'</td></tr>';
                                        }
                                        foreach($html->find('select') as $select){ 
                                            $options = '';
                                            foreach($select->find('option') as $option) {
                                                $options.= '<option value="'.$option->value.'">'.$option->plaintext.'</option>';
                                            }
                                            $selects.= '<tr><td><select id="'.$select->id.'" name="'.$select->name.'" class="'.$select->class.'">'.$options.'</select></td><td>'.$select->name.'</td><td>'.$select->class.'</td><td>'.$select->id.'</td></tr>';
                                        }
                                        $html->clear();
                                        unset($html);            
                                        echo '<style>#command_result table{border:1px solid #ccc;border-collapse: collapse;color:#666;font-size:13px;}#command_result table tr:nth-child(1){background:#e5e5e5;color:#000;text-transform:uppercase;}</style>';
                                        if(strlen($scripts)){
                                            echo '<h3>Javascripts :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>src</td><td>type</td></tr>'.$scripts.'</table>';
                                        }
                                        if(strlen($stylesheets)){
                                            echo '<h3>Stylesheets :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>href</td><td>rel</td><td>type</td></tr>'.$stylesheets.'</table>';
                                        }
                                        if(strlen($anchors)){
                                            echo '<h3>Anchors :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>href</td><td>class</td><td>id</td></tr>'.$anchors.'</table>';
                                        }
                                        if(strlen($images)){
                                            echo '<h3>Images :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>src</td><td>class</td><td>id</td></tr>'.$images.'</table>';
                                        }
                                        if(strlen($selects)){
                                            echo '<h3>Select Boxes :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>select</td><td>name</td><td>class</td><td>id</td></tr>'.$selects.'</table>';
                                        }
                                        if(strlen($iframes)){
                                            echo '<h3>Select Boxes :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>src</td><td>class</td><td>id</td></tr>'.$iframes.'</table>';
                                        }
                                        if(strlen($tables)){
                                            echo '<h3>Table :-</h3>';
                                            echo '<table width="100%" border="1" cellspacing="1" cellpadding="5"><tr><td>table</td><td>class</td><td>id</td></tr>'. $tables.'</table>';
                                        }
                                    }else{?>
                                        <textarea name="result" placeholder="Result"><?php echo htmlspecialchars($result); ?></textarea>
                                <?php }?>
                            </div>
                        </div>
                    </form>

                </div>
            </section>
            
        </div>

        <div id="footer"> <ul class="copyright"> <li>Copyright &copy; <?php echo date('Y');?> RN Kushwaha. All rights reserved.</li> </ul>
        </div>
        <script src="<?php echo $APP_URL;?>assets/js/jquery.min.js"></script>
        <script src="<?php echo $APP_URL;?>assets/js/jquery.scrolly.min.js"></script>
        <script src="<?php echo $APP_URL;?>assets/js/jquery.scrollzer.min.js"></script>
        <script src="<?php echo $APP_URL;?>assets/js/skel.min.js"></script>
        <script src="<?php echo $APP_URL;?>assets/js/util.js"></script>
        <script src="<?php echo $APP_URL;?>assets/js/main.js"></script>
        <script src="<?php echo $APP_URL;?>/assets/js/jquery.tabledit.js"></script>
        <script>
        $('#hide_sidebar').on('click',function(e){
            $('#footer,#main').css({'margin-left':'0px'});
            $('#header').css({'left':'-300px'});
            $('.container').css({'width':'90%'});
            $('#show_sidebar').css({'left':'0px'});
        })
        $('#show_sidebar').on('click',function(e){
            $('#footer,#main').css({'margin-left':'300px'});
            $('#header').css({'left':'0px'});
            $('.container').css({'width':''});
            $('#show_sidebar').css({'left':'-300px'});
        })
        $().ready(function($) {
           $('.copyText').on('click',function(){
                var txt = $(this).parents('td').text().trim();
                var tmp = $('<input>');
                tmp.val(txt);
                $('body').append(tmp);
                tmp.select();
                document.execCommand('copy');
                tmp.remove();
                var msgbox = $('<div class="msg_box"></div>');
                $('#outer').append(msgbox);

                var textType = $(this).data('type');
                if( textType!='' ) textType = textType+' ';
                msgbox.addClass('green_bg').html("<h3>Success!</h3>"+textType+"Copied Successfully!").fadeIn(500).delay(2000).fadeOut(700);
           });

           $('.radio').on('click',function(e){
                if($(this).val()=='keepalive'){
                    $('#keepalive').show();
                    $('#db').hide();
                }else if($(this).val()=='sql'){
                    $('#keepalive').hide();
                    $('#db').show();
                }else{
                    $('#keepalive,#db').hide();
                }
           }); 

           <?php if ($_REQUEST['encode']== 'keepalive') {
                $_REQUEST['keepalive_dur'] = ($_REQUEST['keepalive_dur']>0 ? $_REQUEST['keepalive_dur'] : 240);
                $urls = explode('<br/>',str_replace('<br />','<br/>',nl2br($_REQUEST['command'])));
                ?>
                
                function keepAlive(){
                    var r=0;
                    setInterval(function(){
                        <?php $tab=1;foreach($urls as $url){?>
                            var str<?php echo $tab;?> = "<?php echo strip_tags(trim($url));?>";
                            str<?php echo $tab;?>=str<?php echo $tab;?>.replace(/(?:\r\n|\r|\n)/g, '<br />');
                            if(popup<?php echo $tab;?> ==undefined){
                                var popup<?php echo $tab;?> = window.open(str<?php echo $tab;?>,'commloan<?php echo $tab;?>','height=500,width=500,scroolbars=1');
                            }
                        <?php $tab++;}?>
                            setTimeout(function(){
                                <?php $tab=1;foreach($urls as $url){?>
                                    if(popup<?php echo $tab;?> !=undefined){
                                        popup<?php echo $tab;?>.location.reload();
                                    }else{
                                        var popup<?php echo $tab;?> = window.open(str<?php echo $tab;?>,'commloan<?php echo $tab;?>','height=500,width=500,scroolbars=1');
                                    }
                                <?php $tab++;}?>
                            }, <?php echo ($_REQUEST['keepalive_dur'])*1000;?>);
                        
                        r++;
                        $('#requests').text('('+r+' times request sent)');
                    }, <?php echo $_REQUEST['keepalive_dur']*1000;?>);
                }

                keepAlive();

                var t=0;
                setInterval(function(){
                    t++;
                    if(t*1000> <?php echo $_REQUEST['keepalive_dur']*1000;?> ) t=1;
                    $('#timer').text('('+t+' Sec)');
                },1000);
                
          <?php }?>
        });

        $('#password_table').Tabledit({
            columns: {
                identifier: [0, 'id'],
                editable: [[2, 'username'],[3, 'password']]
            },
            hideIdentifier: true,
            buttons: {
                edit: {
                    class: 'btn btn-sm btn-warning',
                    html: '<span class="icon fa-edit"></span>'
                },
                remove: {
                    class: 'btn btn-sm btn-danger',
                    html: '<span class="icon fa-trash"></span>'
                },
                save: {
                    class: 'btn btn-sm btn-success',
                    html: '<span class="icon fa-rotate-right"></span> &nbsp; Save'
                },
                cancel: {
                    class: 'btn btn-sm btn-warning',
                    html: '<span class="icon fa-edit"></span>'
                }
            },
        });
        </script>
    </body>
</html>