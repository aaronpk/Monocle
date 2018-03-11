          <? /* video player */ ?>
          <? if(isset($entry['video'])): ?>
            <div class="videos">
              <? foreach($entry['video'] as $i=>$video): ?>
                <video src="<?= $video ?>" class="video u-video" controls <?= isset($entry['photo'][$i]) ? 'poster="'.$entry['photo'][$i].'"' : '' ?>>
              <? endforeach ?>
              <div class="videoclear"></div>
            </div>
          <? elseif(isset($entry['photo'])): ?>
            <? /* photos */ ?>
            <div class="photos">
              <? if(count($entry['photo']) > 1): ?>
                <div class="multi-photo photos-<?= count($entry['photo']) ?>">
                  <? foreach($entry['photo'] as $photo): ?>
                    <a href="<?= $photo ?>" data-featherlight="<?= $photo ?>" class="photo" style="background-image:url(<?= $photo ?>);">
                      <img src="<?= $photo ?>" class="post-img u-photo">
                    </a>
                  <? endforeach ?>
                  <div class="multi-photo-clear"></div>
                </div>
              <? else: ?>
                <img src="<?= $entry['photo'][0] ?>" class="photo u-photo">
              <? endif ?>
              <div class="photoclear"></div>
            </div>
          <? endif ?>
