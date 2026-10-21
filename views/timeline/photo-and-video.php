          <?php /* video player */ ?>
          <?php if(isset($entry['video'])): ?>
            <div class="videos">
              <?php foreach($entry['video'] as $i=>$video): ?>
                <video src="<?= $video ?>" class="video u-video" controls <?= isset($entry['photo'][$i]) ? 'poster="'.$entry['photo'][$i].'"' : '' ?>>
              <?php endforeach ?>
              <div class="videoclear"></div>
            </div>
          <?php elseif(isset($entry['photo'])): ?>
            <?php /* photos */ ?>
            <div class="photos">
              <?php if(is_array($entry['photo']) && count($entry['photo']) > 1): ?>
                <div class="multi-photo photos-<?= count($entry['photo']) ?>">
                  <?php foreach($entry['photo'] as $photo): ?>
                    <a href="<?= image_proxy($photo) ?>" data-featherlight="<?= image_proxy($photo) ?>" class="photo" <?= defined('LAZYLOAD') ? 'data-lazy-' : '' ?>style="background-image:url(<?= image_proxy($photo) ?>);">
                      <img src="<?= image_proxy($photo) ?>" class="post-img u-photo">
                    </a>
                  <?php endforeach ?>
                  <div class="multi-photo-clear"></div>
                </div>
              <?php else: ?>
                <?php if(is_array($entry['photo'])): ?>
                  <img <?= defined('LAZYLOAD') ? 'src="'.image_placeholder($entry, image_proxy($entry['photo'][0])).'" data-lazy-' : '' ?>src="<?= image_proxy($entry['photo'][0]) ?>" class="photo u-photo">
                <?php else: ?>
                  <img <?= defined('LAZYLOAD') ? 'src="'.image_placeholder($entry, image_proxy($entry['photo'])).'" data-lazy-' : '' ?>src="<?= image_proxy($entry['photo']) ?>" class="photo u-photo">
                <?php endif ?>
              <?php endif ?>
              <div class="photoclear"></div>
            </div>
          <?php endif ?>
