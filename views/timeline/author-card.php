          <div class="author u-author h-card">
            <? if(!empty($entry['author']['photo'])): ?>
              <a href="/channel/<?= e($channel['uid']) ?>/<?= e($entry['_source']) ?>"><img src="<?= e($entry['author']['photo']) ?>" class="u-photo"></a>
            <? endif ?>
            <? if(!empty($entry['author']['name']) || !empty($entry['author']['url'])): ?>
              <div class="author-name">
                <? if(!empty($entry['author']['url'])): ?>
                  <? if(!empty($entry['author']['name'])): ?>
                    <a href="/channel/<?= e($channel['uid']) ?>/<?= e($entry['_source']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                  <? endif ?>
                  <a href="<?= e($entry['author']['url']) ?>" class="url u-url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
                <? elseif(!empty($entry['author']['name'])): ?>
                  <span class="name p-name"><?= e($entry['author']['name']) ?></span>
                <? endif ?>
              </div>
            <? endif ?>
          </div>
