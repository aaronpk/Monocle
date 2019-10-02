          <div class="author u-author h-card">
            <? if(!empty($entry['author']['photo'])): ?>
              <? if(isset($entry['_source']) && isset($channel)): ?>
                <a href="/channel/<?= e($channel['uid']) ?>/<?= e($entry['_source']) ?>">
                  <img <?= defined('LAZYLOAD') ? 'src="/images/no-profile-photo.png" data-lazy-' : '' ?>src="<?= e(image_proxy($entry['author']['photo'], '90x90')) ?>" class="u-photo">
                </a>
              <? else: ?>
                <img <?= defined('LAZYLOAD') ? 'src="/images/no-profile-photo.png" data-lazy-' : '' ?>src="<?= e(image_proxy($entry['author']['photo'], '90x90')) ?>" class="u-photo">
              <? endif ?>
            <? endif ?>
            <? if(!empty($entry['author']['name']) || !empty($entry['author']['url'])): ?>
              <div class="author-name">
                <? if(!empty($entry['author']['url'])): ?>
                  <? if(!empty($entry['author']['name'])): ?>
                    <? if(isset($entry['_source']) && isset($channel)): ?>
                      <a href="/channel/<?= e($channel['uid']) ?>/<?= e($entry['_source']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                    <? else: ?>
                      <a href="<?= e($entry['author']['url']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                    <? endif ?>
                  <? endif ?>
                  <a href="<?= e($entry['author']['url']) ?>" class="url u-url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
                <? elseif(!empty($entry['author']['name'])): ?>
                  <span class="name p-name"><?= e($entry['author']['name']) ?></span>
                <? endif ?>
              </div>
            <? endif ?>
          </div>
