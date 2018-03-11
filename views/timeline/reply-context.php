          <? if(!empty($entry['in-reply-to'])): ?>
            <div class="context">
              <? foreach($entry['in-reply-to'] as $r): ?>
                <div class="in-reply-to"><i class="fas fa-reply"></i> <a href="<?= $r ?>"><?= e(\p3k\url\display_url($r)) ?></a></div>
              <? endforeach ?>
            </div>
          <? endif ?>
