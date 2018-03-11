            <div class="content">
              <span class="author">
                <? if(!empty($entry['author']['photo'])): ?>
                  <img src="<?= e($entry['author']['photo']) ?>">
                <? endif ?>
                <? if(!empty($entry['author']['url'])): ?>
                  <a href="<?= e($entry['author']['url']) ?>">
                    <?= e($entry['author']['name'] ?? \p3k\url\display_url($entry['author']['url'])) ?>
                  </a>
                <? else: ?>
                  someone
                <? endif ?>
              </span>
              liked
              <? if(count($entry['like-of']) == 1): ?>
                <a href="<?= e($entry['like-of'][0]) ?>">a post
                on <?= parse_url($entry['like-of'][0], PHP_URL_HOST) ?></a>
              <? else: ?>
                <? foreach($entry['like-of'] as $i=>$l): ?>
                  <?= $i == count($entry['like-of']) - 1 ? 'and' : '' ?>
                  <a href="<?= e($l) ?>"><?= e(\p3k\url\display_url($l)) ?></a>
                <? endforeach ?>
              <? endif ?>
            </div>
