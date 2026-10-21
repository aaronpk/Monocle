          <?php if(!empty($entry['name'])): ?>
            <h2 class="name p-name"><a href="<?= e($entry['url'] ?? '') ?>"><?= e($entry['name']) ?></a></h2>

            <div class="content html">
              <?php if(!empty($entry['summary'])): ?>
                <span class="p-summary"><?= e($entry['summary']) ?></span>
              <?php endif ?>
              <div class="read-more summary"><a href="<?= e($entry['url'] ?? '') ?>">Read More</a></div>
            </div>

          <?php else: ?>

            <?php if(!empty($entry['content']['html'])): ?>
              <div class="content html">
                <span class="e-content"><?= p3k\HTML::sanitize($entry['content']['html']) ?></span>
                <div class="read-more hidden"><a href="#" class="">Read More</a></div>
              </div>
            <?php elseif(!empty($entry['content']['text'])): ?>
              <div class="content text"><span class="p-content"><?= e($entry['content']['text']) ?></span> <div class="read-more hidden"><a href="#" class="">Read More</a></div></div>
            <?php endif ?>

          <?php endif ?>

          <?php
          // If no other content is available to be displayed, show the summary instead
          if(
            !isset($entry['checkin'])
            && empty($entry['name'])
            && empty($entry['content'])
            && empty($entry['audio'])
            && empty($entry['video'])
            && empty($entry['photo'])
          ):
          ?>
            <div class="content">
              <?php if(!empty($entry['summary'])): ?>
                <div class="text"><span class="p-summary"><?= e($entry['summary']) ?></span></div>
              <?php else: ?>
                <div class="content text"></div>
              <?php endif ?>
            </div>
          <?php endif ?>
