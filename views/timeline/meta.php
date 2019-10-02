          <div class="meta">
            <? $empty = true; ?>
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
              <? $empty = false; ?>
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
              <? $empty = false; ?>
            <? elseif(!empty($entry['url'])): ?>
              <a href="<?= e($entry['url']) ?>" class="u-url u-uid">
                <?= e(\p3k\url\display_url($entry['url'])) ?>
              </a>
              <? $empty = false; ?>
            <? endif ?>
            <? if(!empty($entry['syndication'])): ?>
              <span class="syndication">
              <?
              foreach($entry['syndication'] as $syn):
                $host = parse_url($syn, PHP_URL_HOST);
                if($host == 'twitter.com' || $host == 'www.twitter.com')        $icon = 'twitter';
                elseif($host == 'facebook.com' || $host == 'www.facebook.com')  $icon = 'facebook';
                elseif($host == 'instagram.com')                                $icon = 'instagram';
                elseif($host == 'linkedin.com')                                 $icon = 'linkedin';
                elseif($host == 'github.com')                                   $icon = 'github';
                elseif($host == 'medium.com')                                   $icon = 'medium';
                elseif($host == 'swarmapp.com' || $host == 'foursquare.com')    $icon = 'foursquare';
                elseif($host == 'amazon.com')                                   $icon = 'amazon';
                elseif($host == 'flickr.com')                                   $icon = 'flickr';
                elseif($host == 'meetup.com')                                   $icon = 'meetup';
                elseif($host == 'slideshare.net')                               $icon = 'slideshare';
                elseif($host == 'news.ycombinator.com')                         $icon = 'y-combinator';
                elseif($host == 'soundcloud.com')                               $icon = 'soundcloud';
                elseif($host == 'yelp.com')                                     $icon = 'yelp';
                elseif($host == 'youtube.com')                                  $icon = 'youtube';
                else                                                            $icon = 'link';
                echo '<a href="'.$syn.'" class="u-syndication">'.fa($icon, 'brands').'</a> ';
              endforeach
              ?>
              </span>
              <? $empty = false; ?>
            <? endif ?>
            <? if($empty): ?>
              &nbsp;
            <? endif ?>
          </div>
