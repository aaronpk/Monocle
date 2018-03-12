          <? if(!empty($entry['in-reply-to'])): ?>
            <div class="context">
              <? foreach($entry['in-reply-to'] as $r): ?>
                <div class="in-reply-to"><i class="fas fa-reply"></i> <a href="<?= $r ?>" class="u-in-reply-to"><?= e(\p3k\url\display_url($r)) ?></a></div>
              <? endforeach ?>
            </div>
          <? endif ?>
          <? if(!empty($entry['bookmark-of'])): ?>
            <div class="context">
              <? foreach($entry['bookmark-of'] as $r): ?>
                <div class="bookmark-of"><i class="fas fa-bookmark"></i> <a href="<?= $r ?>" class="u-bookmark-of"><?= e(\p3k\url\display_url($r)) ?></a></div>
              <? endforeach ?>
            </div>
          <? endif ?>
