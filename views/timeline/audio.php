          <?php if(!empty($entry['audio'])): ?>
            <?php foreach($entry['audio'] as $audio): ?>
              <audio src="<?= e($audio) ?>" controls class="u-audio"></audio>
            <?php endforeach ?>
          <?php endif ?>
