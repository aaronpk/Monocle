          <? if(!empty($entry['name'])): ?>
            <h2 class="name"><?= e($entry['name']) ?></h2>
          <? endif ?>

          <? if(!empty($entry['content']['html'])): ?>
            <div class="content html">
              <?= $entry['content']['html'] ?>
              <div class="read-more hidden"><a href="#" class="">Read More</a></div>
            </div>
          <? elseif(!empty($entry['content']['text'])): ?>
            <div class="content text"><?= e($entry['content']['text']) ?> <div class="read-more hidden"><a href="#" class="">Read More</a></div></div>
          <? endif ?>

          <? /* add padding if there is no name or content */ ?>
          <? if(empty($entry['name']) && empty($entry['checkin']) && empty($entry['content']['html']) && empty($entry['content']['text'])): ?>
            <div class="content text"></div>
          <? endif ?>
