          <? if(isset($entry['quotation-of']) && isset($entry['refs'][$entry['quotation-of']])): ?>
            <div class="quotation"><div class="quotation-post u-quotation-of h-cite">
              <div class="quotation-post-container">
                <? $quote = $entry['refs'][$entry['quotation-of']]; ?>

                <? if(isset($quote['photo'])): ?>
                  <div class="qphoto">
                    <a href="<?= e($quote['url']) ?>"><img src="<?= image_proxy($quote['photo'][0]) ?>" class="photo u-photo"></a>
                  </div>
                <? endif ?>

                <div class="qcontent">
                  <? if(isset($quote['author'])): ?>
                    <div class="author u-author h-card">
                      <span class="p-name"><?= e($quote['author']['name']) ?></span>
                      <a class="u-url" href="<?= e($quote['author']['url']) ?>"><?= e($quote['author']['url']) ?></a>
                    </div>
                  <? endif ?>

                  <? if(isset($quote['content'])): ?>
                    <div class="content p-content">
                      <?= e(strlen($quote['content']['text']) > 170 ? substr($quote['content']['text'],0,170).'...' : $quote['content']['text']) ?>
                    </div>
                  <? endif ?>

                  <div class="permalink">
                    <a href="<?= e($quote['url']) ?>">Show original</a>
                  </div>
                </div>

              </div>
            </div></div>
          <? endif ?>

