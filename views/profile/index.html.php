<?php
/**
* @var $username
*/
?>
<h1>Profile</h1>
<p>Welcome, <?= htmlspecialchars($username) ?>!</p>
<p>You've got a <strong>promocode</strong>, <a href="/profile/getpromo">click the link to open it</a></p>
<a href="/profile/logout">Logout</a>
