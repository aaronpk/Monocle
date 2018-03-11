          <div class="meta">
            <? if(!empty($entry['category'])): ?>
              <div class="categories">
                <? foreach($entry['category'] as $tag): ?>
                  <span class="category"><?= '#'.trim($tag,'#') ?></span>
                <? endforeach ?>
              </div>
            <? endif ?>

            <? if(!empty($entry['published'])): ?>
              <? if(!empty($entry['url'])): ?>
                <a href="<?= e($entry['url']) ?>">
                  <?= display_date('F j, Y g:ia P', $entry['published']) ?>
                </a>
              <? else: ?>
                <?= display_date('F j, Y g:ia P', $entry['published']) ?>
              <? endif ?>
            <? elseif(!empty($entry['url'])): ?>
              <a href="<?= e($entry['url']) ?>">
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
                echo '<a href="'.$syn.'"><i class="'.$icon.'"></i></a> ';
              endforeach
              ?>
              </span>
            <? endif ?>
          </div>
