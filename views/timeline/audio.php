          <? if(!empty($entry['audio'])): ?>
            <? foreach($entry['audio'] as $audio): ?>
              <audio src="<?= e($audio) ?>" controls class="u-audio"></audio>
            <? endforeach ?>
          <? endif ?>
