<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php

if ($prior != '2' or $prior == false) {
    if ($code != '') {
        $code = trim($code);

		if (stristr($code, '<iframe') !== false) {
			$code = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=transparent"$3>', $code);
		}

		if (video_module_is_embed($code) == true) {
			$code = '<div class="mwembed">' . $code . '</div>';
		} else {
			if($use_thumbnail) $autoplay = 1;
			$code = video_module_url2embed($code, $w, $h, $autoplay);
		}

        if($use_thumbnail){

            if(preg_match("/\<iframe.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/",$code,$matches)){
                $src = $matches[1];
                $src_attr = 'src="' . $src . '"';
                $style = 'style="display:none"';
                $embed_data_tag = 'data-src="' . $src . '"';
				$code = str_replace($src_attr, $style . ' src="" ' . $embed_data_tag, $code);
            }

            $code = '<div class="js-embed-'.$params['id'].'"><img src="' . $thumb . '">' . $code . '</div>';
        }
    } else {
    	$show_video_settings_btn = true;
    }
} else if ($prior == '2') {
    if ($upload != '') {
        if ($autoplay == '0') {
            $autoplay = '';
        } else {
            $autoplay = 'autoplay';
        }

        $embed_data_tag = 'src="' . $upload . '"';
        if ($use_thumbnail) {
            $embed_data_tag = 'data-src="' . $upload . '"';
        }

        $code = '<div class="mwembed"><video class="js-embed-'.$params['id'].'" controls width="' . $w . '" height="' . $h . '" ' . $autoplay . ' '.$embed_data_tag.' poster="'. $thumb .'"></video></div>';
    } else {
        $show_video_settings_btn = true;
    }
} else {
	$show_video_settings_btn = true;
}

if($show_video_settings_btn) {
    if(in_live_edit()){
        $code = "<div class='video-module-default-view mw-open-module-settings'><img src='" . $config['url_to_module'] . "video.svg' style='width: 65px; height: 65px;'/></div>";
    }
}
?>

<?php if($use_thumbnail) { ?>
 <script>
    <?php if($prior== '2') { ?>

        $(document).ready(function() {
            $('.js-embed-<?php echo $params['id']; ?>').attr('src', $('.js-embed-<?php echo $params['id']; ?>').attr('data-src'));
        });

    <?php } elseif($prior != '2' or $prior == false) { ?>

        $(document).ready(function() {
            $('.js-embed-<?php echo $params['id']; ?>').click(function() {
				$(this).children("img").hide();
				var iframe = $(this).find("iframe");
				iframe.attr('src',iframe.attr('data-src')).show();
            });
        });

    <?php } ?>
</script>
<?php } ?>

<?php print $code;  ?>
