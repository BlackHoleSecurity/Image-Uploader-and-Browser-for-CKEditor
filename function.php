<?php

function loadImages() {
    require(__DIR__ . '/pluginconfig.php');
    if(file_exists($useruploadpath)){
        
        $filesizefinal = 0;
        $count = 0;
        
        $dir = $useruploadpath;
        $files = glob("$dir*.{jpg,jpeg,png,gif,ico}", GLOB_BRACE);
        usort($files, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
        for($i=count($files)-1; $i >= 0; $i--):
            $image = $files[$i];
            $image_pathinfo = pathinfo($image);
            $image_extension = $image_pathinfo['extension'];
            $image_filename = $image_pathinfo['filename'];
            $size = getimagesize($image);
            $image_height = $size[0];
            $file_size_byte = filesize($image);
            $file_size_kilobyte = ($file_size_byte/1024);
            $file_size_kilobyte_rounded = round($file_size_kilobyte,1);
            $filesizetemp = $file_size_kilobyte_rounded;
            $filesizefinal = round($filesizefinal + $filesizetemp) . " KB";
            $calcsize = round($filesizefinal + $filesizetemp);
            $count = ++$count;
        ?>

            <div class="fileDiv" onclick="showImage('<?php echo $image; ?>','<?php echo $image_height; ?>');">
                <div class="imgDiv"><img class="fileImg lazy" data-original="<?php echo $image; ?>"></div>
                <p class="fileDescription"><span class="fileMime"><?php echo $image_extension; ?></span> <?php echo $image_filename; ?><?php if($file_extens == "yes"){echo ".$image_extension";} ?></p>
                <p class="fileTime"><?php echo date ("F d Y H:i", filemtime($image)); ?></p>
                <p class="fileTime"><?php echo $filesizetemp; ?> KB</p>
            </div>

        <?php endfor;
        if($calcsize >= 1024){
            $filesizefinal = round($filesizefinal/1024,1) . " MB";
        }
        echo "
        <script>
            $( '#finalsize' ).html('$filesizefinal');
            $( '#finalcount' ).html('$count');
        </script>
        ";
    } else {
        echo '<div id="folderError">The folder <b>'.$useruploadfolder.'</b> could not be found. Please choose another folder in the settings or <button class="headerBtn" onclick="window.location.href = \'pluginconfig.php?newfoldername='.$useruploadfolder.'\';">create the folder <b>'.$useruploadfolder.'</b></button>.</div>';
    } 
}

function pathHistory() {
    require(__DIR__ . '/pluginconfig.php');
    $latestpathes = array_slice($foldershistory, -3);
    $latestpathes = array_reverse($latestpathes);
    foreach($latestpathes as $folder) {
        echo '<p class="pathHistory" onclick="useHistoryPath(\''.$folder.'\');">'.$folder.'</p>';
    }
}