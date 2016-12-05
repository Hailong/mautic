Lead,Share,Child Share,Read
<?php foreach ($data as $leadId => $numbers): ?>
Lead<?php echo $leadId ?>,<?php echo $numbers['share_count'] ?>,<?php echo $numbers['child_share_count'] ?>,<?php echo $numbers['read_count'] ?>

<?php endforeach ?>
