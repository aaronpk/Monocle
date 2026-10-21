          <div class="author u-author h-card">
            <?php if(!empty($entry['author']['photo'])): ?>
              <?php if(isset($entry['_source']) && isset($channel)): ?>
                <a href="/channel/<?= e($channel['uid']) ?>/<?= e($entry['_source']) ?>">
                  <img <?= defined('LAZYLOAD') ? 'src="/images/no-profile-photo.png" data-lazy-' : '' ?>src="<?= e(image_proxy($entry['author']['photo'], '90x90')) ?>" class="u-photo">
                </a>
              <?php else: ?>
                <img <?= defined('LAZYLOAD') ? 'src="/images/no-profile-photo.png" data-lazy-' : '' ?>src="<?= e(image_proxy($entry['author']['photo'], '90x90')) ?>" class="u-photo">
              <?php endif ?>
            <?php endif ?>
            <?php if(!empty($entry['author']['name']) || !empty($entry['author']['url'])): ?>
              <div class="author-name">
                <?php if(!empty($entry['author']['url'])): ?>
                  <?php if(!empty($entry['author']['name'])): ?>
                    <?php if(isset($entry['_source']) && isset($channel)): ?>
                      <a href="/channel/<?= e($channel['uid']) ?>/<?= e($entry['_source']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                    <?php else: ?>
                      <a href="<?= e($entry['author']['url']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                    <?php endif ?>
                  <?php endif ?>
                  <a href="<?= e($entry['author']['url']) ?>" class="url u-url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
                <?php elseif(!empty($entry['author']['name'])): ?>
                  <span class="name p-name"><?= e($entry['author']['name']) ?></span>
                <?php endif ?>
              </div>
            <?php endif ?>
          </div>
