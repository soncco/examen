<?php if (isset($results['first_page']) || isset($results['last_page'])): ?>
<div class="paginator">
    <p>
    <strong>Página</strong>: <?php print $results['current_page']; ?> de <?php print $results['num_pages']; ?>
    (<?php print $results['num_records']; ?> items)
    <?php if(isset($results['first_page'])) print $results['first_page']; ?>
    <?php if(isset($results['prev'])) print $results['prev']; ?>
    <?php print $results['nav']; ?>
    <?php if(isset($results['next'])) print $results['next']; ?>
    <?php if(isset($results['last_page'])) print $results['last_page']; ?>
    </p>
</div>
<?php endif; ?>