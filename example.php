<?php
require_once dirname(__FILE__) . '/class.whatsbulk.php';

# initiate
$whatsbulk = new Whatsbulk();
# formatting output
echo "<pre>";

# show balance object
print_r($whatsbulk->getBalance());


# show balance object
print_r($whatsbulk->getLists());

# show available queues
print_r($whatsbulk->getQueues());

echo "</pre>";