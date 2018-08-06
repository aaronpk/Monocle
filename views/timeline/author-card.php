          <div class="author u-author h-card">
            <? if(!empty($entry['author']['name']) && !empty($entry['author']['photo']) && !empty($entry['author']['url'])): ?>
              <img src="<?= e($entry['author']['photo']) ?>" class="u-photo">
              <div class="author-name">
                <a href="<?= e($entry['author']['url']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                <a href="<?= e($entry['author']['url']) ?>" class="url u-url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
              </div>
            <? elseif(!empty($entry['author']['url'])): ?>
              <div class="author-name">
                <a href="<?= e($entry['author']['url']) ?>" class="name p-name"><?= e($entry['author']['name']) ?></a>
                <a href="<?= e($entry['author']['url']) ?>" class="url u-url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
              </div>
            <? elseif(!empty($entry['author']['name'])): ?>
              <div class="author-name">
                  <span class="name p-name"><?= e($entry['author']['name']) ?></span>
              </div>
            <? else: ?>

            <? endif ?>
          </div>
