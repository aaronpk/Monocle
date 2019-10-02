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
              <? if(is_array($entry['photo']) && count($entry['photo']) > 1): ?>
                <div class="multi-photo photos-<?= count($entry['photo']) ?>">
                  <? foreach($entry['photo'] as $photo): ?>
                    <a href="<?= image_proxy($photo) ?>" data-featherlight="<?= image_proxy($photo) ?>" class="photo" <?= defined('LAZYLOAD') ? 'data-lazy-' : '' ?>style="background-image:url(<?= image_proxy($photo) ?>);">
                      <img src="<?= image_proxy($photo) ?>" class="post-img u-photo">
                    </a>
                  <? endforeach ?>
                  <div class="multi-photo-clear"></div>
                </div>
              <? else: ?>
                <?php if(is_array($entry['photo'])): ?>
                  <img <?= defined('LAZYLOAD') ? 'src="'.image_placeholder($entry, image_proxy($entry['photo'][0])).'" data-lazy-' : '' ?>src="<?= image_proxy($entry['photo'][0]) ?>" class="photo u-photo">
                <?php else: ?>
                  <img <?= defined('LAZYLOAD') ? 'src="'.image_placeholder($entry, image_proxy($entry['photo'])).'" data-lazy-' : '' ?>src="<?= image_proxy($entry['photo']) ?>" class="photo u-photo">
                <?php endif ?>
              <? endif ?>
              <div class="photoclear"></div>
            </div>
          <? endif ?>
