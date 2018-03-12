          <div class="meta">
            <? if(!empty($entry['category'])): ?>
              <div class="categories">
                <? foreach($entry['category'] as $tag): if(trim($tag)): ?>
                  <? if(preg_match('~https?://~', $tag)): ?>
                    <span class="category"><a href="<?= e($tag) ?>" class="u-category"><?= \p3k\url\display_url($tag) ?></a></span>
                  <? else: ?>
                    <span class="category">#<span class="p-category"><?= trim($tag,'#') ?></span></span>
                  <? endif ?>
                <? endif; endforeach ?>
              </div>
            <? endif ?>

            <? if(!empty($entry['published'])): ?>
              <? if(!empty($entry['url'])): ?>
                <a href="<?= e($entry['url']) ?>" class="u-url u-uid">
                  <time class="dt-published" datetime="<?= display_date('c', $entry['published']) ?>">
                    <?= display_date('F j, Y g:ia P', $entry['published']) ?>
                  </time>
                </a>
              <? else: ?>
                <time class="dt-published" datetime="<?= display_date('c', $entry['published']) ?>">
                  <?= display_date('F j, Y g:ia P', $entry['published']) ?>
                </time>
              <? endif ?>
            <? elseif(!empty($entry['url'])): ?>
              <a href="<?= e($entry['url']) ?>" class="u-url u-uid">
                <?= e(\p3k\url\display_url($entry['url'])) ?>
              </a>
            <? endif ?>
            <? if(!empty($entry['syndication'])): ?>
              <span class="syndication">
              <?
              foreach($entry['syndication'] as $syn):
                $host = parse_url($syn, PHP_URL_HOST);
                if($host == 'twitter.com' || $host == 'www.twitter.com')
                  $icon = 'fab fa-twitter';
                elseif($host == 'facebook.com' || $host == 'www.facebook.com')
                  $icon = 'fab fa-facebook';
                elseif($host == 'github.com')
                  $icon = 'fab fa-github';
                else
                  $icon = 'fas fa-link';
                echo '<a href="'.$syn.'" class="u-syndication"><i class="'.$icon.'"></i></a> ';
              endforeach
              ?>
              </span>
            <? endif ?>
          </div>
