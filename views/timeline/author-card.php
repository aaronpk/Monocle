          <div class="author">
            <? if(!empty($entry['author']['name']) && !empty($entry['author']['photo']) && !empty($entry['author']['url'])): ?>
              <img src="<?= e($entry['author']['photo']) ?>">
              <div class="author-name">
                <a href="<?= e($entry['author']['url']) ?>" class="name"><?= e($entry['author']['name']) ?></a>
                <a href="<?= e($entry['author']['url']) ?>" class="url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
              </div>
            <? elseif(!empty($entry['author']['url'])): ?>
              <div class="author-name">
                <a href="<?= e($entry['author']['url']) ?>" class="name"><?= e($entry['author']['name']) ?></a>
                <a href="<?= e($entry['author']['url']) ?>" class="url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
              </div>
            <? else: ?>

            <? endif ?>
          </div>
