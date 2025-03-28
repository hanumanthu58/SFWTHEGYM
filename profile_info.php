<?php
 
?>
<div class="profile-header">
    <div class="profile-pic-container">
        <img src="<?= htmlspecialchars($profile['profile_pic']) ?: 'default-pic.jpg' ?>" alt="Profile Picture" class="profile-pic">
    </div>
    <div class="profile-info">
        <h1 class="profile-name"><?= htmlspecialchars($profile['name']) ?></h1>
        <p class="profile-bio"><?= htmlspecialchars($profile['bio']) ?></p>
        <div class="profile-details">
            <p><strong>Age:</strong> <?= htmlspecialchars($profile['age']) ?></p>
            <p><strong>Height:</strong> <?= htmlspecialchars($profile['height']) ?></p>
            <p><strong>Weight:</strong> <?= htmlspecialchars($profile['weight']) ?></p>
            <p><strong>User ID:</strong> <?= htmlspecialchars($userId) ?></p>
        </div>
    </div>
</div>
