          <? if(!empty($entry['name'])): ?>
            <h2 class="name p-name"><a href="<?= e($entry['url']) ?>"><?= e($entry['name']) ?></a></h2>

            <div class="content html">
              <? if(!empty($entry['summary'])): ?>
                <span class="p-summary"><?= e($entry['summary']) ?></span>
              <? endif ?>
              <div class="read-more summary"><a href="<?= e($entry['url']) ?>">Read More</a></div>
            </div>

          <? else: ?>

            <? if(!empty($entry['content']['html'])): ?>
              <div class="content html">
                <span class="e-content"><?= $entry['content']['html'] ?></span>
                <div class="read-more hidden"><a href="#" class="">Read More</a></div>
              </div>
            <? elseif(!empty($entry['content']['text'])): ?>
              <div class="content text"><span class="p-content"><?= e($entry['content']['text']) ?></span> <div class="read-more hidden"><a href="#" class="">Read More</a></div></div>
            <? endif ?>

          <? endif ?>

          <?
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
              <? if(!empty($entry['summary'])): ?>
                <div class="text"><span class="p-summary"><?= e($entry['summary']) ?></span></div>
              <? else: ?>
                <div class="content text"></div>
              <? endif ?>
            </div>
          <? endif ?>
