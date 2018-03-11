          <? if(!empty($entry['name'])): ?>
            <h2 class="name p-name"><?= e($entry['name']) ?></h2>
          <? endif ?>

          <? if(!empty($entry['content']['html'])): ?>
            <div class="content html">
              <span class="e-content"><?= $entry['content']['html'] ?></span>
              <div class="read-more hidden"><a href="#" class="">Read More</a></div>
            </div>
          <? elseif(!empty($entry['content']['text'])): ?>
            <div class="content text"><span class="p-content"><?= e($entry['content']['text']) ?></span> <div class="read-more hidden"><a href="#" class="">Read More</a></div></div>
          <? endif ?>

          <? /* add padding if there is no name or content */ ?>
          <? if(empty($entry['name']) && empty($entry['checkin']) && empty($entry['content']['html']) && empty($entry['content']['text'])): ?>
            <div class="content text"></div>
          <? endif ?>
